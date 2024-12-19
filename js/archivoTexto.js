/**********************************************************************************************************************/
/********************************************************TABLA BITACORA***********************************************/
/**********************************************************************************************************************/

document.getElementById('btnTxtBitacora').addEventListener('click', () => {
    document.getElementById('fileInputTxt').click();
});

document.getElementById('fileInputTxt').addEventListener('change', async (event) => {
    const files = event.target.files; // Todos los archivos seleccionados
    console.log("Archivos seleccionados:", files);  // Verifica los archivos seleccionados

    if (files.length > 0) {
        for (const file of files) {
            console.log("Procesando archivo:", file.name);

            // Procesar cada archivo y enviar los datos al backend
            const dataToBackend = await manejarArchivo(file);
            const dataFromBackend = await enviarDatosAlBackend(dataToBackend);

            if (dataFromBackend.length > 0) {
                // Solo actualiza si dataFromBackend tiene datos
                actualizarContenidoArchivo(file, dataFromBackend);
            } else {
                console.error(`No se recibieron datos válidos del backend para el archivo ${file.name}.`);
            }
        }
    } else {
        console.error("No se seleccionaron archivos.");
    }
});

async function manejarArchivo(file) {
    const reader = new FileReader();

    return new Promise((resolve, reject) => {
        reader.onload = async (event) => {
            const contenido = event.target.result;

            // Dividir las líneas del archivo
            const lineas = contenido.split(/\r?\n/);

            // Filtrar las líneas que contienen datos válidos
            const datos = lineas
                .map((linea) => linea.trim())
                .filter((linea) => /^[0-9]+\s+\w+/.test(linea))
                .map((linea) => {
                    const partes = linea.split(/\s+/);
                    return partes.length >= 6
                        ? { storBin: partes[1], materialNo: partes[5] }
                        : null;
                })
                .filter(Boolean);

            // Resolvemos la promesa con los datos procesados
            resolve(datos);
        };

        reader.onerror = (error) => {
            reject("Error al leer el archivo: " + error);
        };

        reader.readAsText(file);
    });
}

async function actualizarContenidoArchivo(file, dataFromBackend) {
    const reader = new FileReader();

    reader.onload = function (event) {
        const originalContent = event.target.result;
        const originalLines = originalContent.split(/\r?\n/); // Divide el archivo en líneas
        const noMatchData = [];
        const updatedLines = originalLines.map((line) => {
            // Divide la línea en partes basándose en espacios/tabulaciones
            const parts = line.trim().split(/\s+/);

            if (parts.length >= 6) {
                const storBin = parts[1]; // `storBin` es el segundo elemento
                const materialNo = parts[5]; // `materialNo` es el sexto elemento

                // Buscar coincidencia en dataFromBackend
                const matchingData = dataFromBackend.find(
                    (item) => item.storBin === storBin && item.materialNo === materialNo
                );

                if (matchingData) {
                    return line.replace("______________", matchingData.conteoFinal);
                } else {
                    // Si no se encontró una coincidencia, guardar los datos en noMatchData
                    noMatchData.push({ storBin,materialNo });
                }
            }

            return line; // Mantener la línea sin cambios si no hay coincidencia
        });

        const finalContent = updatedLines.join("\n"); // Unir las líneas actualizadas
        const blob = new Blob([finalContent], { type: "text/plain" });

        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = `actualizado_${file.name}`;
        link.click();
    };

    reader.readAsText(file);
}

async function enviarDatosAlBackend(data) {
    try {
        const response = await fetch('daoAdmin/daoActualizar-txt.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });
        return await response.json(); // Devolvemos los datos procesados por el backend
    } catch (error) {
        console.error('Error enviando datos al backend:', error);
        return [];
    }
}

async function enviarDatosAlBackendAux(data) {
    try {
        const response = await fetch('daoAdmin/daoActualizar-txtAux.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });
        return await response.json(); // Devolvemos los datos procesados por el backend
    } catch (error) {
        console.error('Error enviando datos al backend:', error);
        return [];
    }
}

function descargarDataFromBackendPro(dataFromBackend) {
    var wb = XLSX.utils.book_new();
    wb.Props = {
        Title: "SheetJS",
        Subject: "Numeros de parte faltantes",
        Author: "Hadbetsito",
        CreatedDate: new Date()
    };
    wb.SheetNames.push("Test Sheet");
    var ws_data = [['InventoryItem', 'Record', 'Bin', 'Bin/n', 'Contador', 'Numero Parte', 'Plant','Cantidad','Sun','Type']]; // Encabezados de las columnas

    var storBinCounts = {}; // Para llevar un registro de los 'StorBin' que ya hemos visto

    for (var i = 0; i < dataFromBackend.length; i++) {
        var storageUnit = dataFromBackend[i].storageUnit;
        var inventoryItem = dataFromBackend[i].inventoryItem;
        var storage_Bin = dataFromBackend[i].storage_Bin;
        var invRecount = dataFromBackend[i].invRecount;
        var numeroParte = dataFromBackend[i].numero_Parte;
        var cantidad = dataFromBackend[i].cantidad;
        var plan = dataFromBackend[i].plan;
        var storage_Type = dataFromBackend[i].storage_Type;

        // Si 'numeroParte' está vacío, saltar esta iteración
        if (!numeroParte) {
            continue;
        }

        // Si 'StorBin' no comienza con 'P', añadir un contador al final
        var storage_Bin_Modified = storage_Bin;
        if (!storage_Bin.startsWith('P')) {
            storage_Bin_Modified = storage_Bin + '/' + (storBinCounts[storage_Bin] || 1);
            storBinCounts[storage_Bin] = (storBinCounts[storage_Bin] || 0) + 1;
        }

        ws_data.push([inventoryItem, invRecount, storage_Bin, storage_Bin+"/"+storBinCounts[storage_Bin], storBinCounts[storage_Bin], numeroParte, plan, cantidad,storageUnit, storage_Type]);
    }

    var ws = XLSX.utils.aoa_to_sheet(ws_data);
    wb.Sheets["Test Sheet"] = ws;
    var wbout = XLSX.write(wb, {bookType:'xlsx',  type: 'binary'});

    function s2ab(s) {
        var buf = new ArrayBuffer(s.length);
        var view = new Uint8Array(buf);
        for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
        return buf;
    }

    saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), 'Numeros de parte faltantes.xlsx');
}


/**********************************************************************************************************************/
/*****************************************************TABLA STORAGE_UNIT***********************************************/
/**********************************************************************************************************************/
document.getElementById('btnTxtStorage').addEventListener('click', () => {
    document.getElementById('fileInputTxtS').click();
});

document.getElementById('fileInputTxtS').addEventListener('change', async (event) => {
    const files = Array.from(event.target.files); // Todos los archivos seleccionados
    console.log("Archivos seleccionados:", files); // Verificar los archivos

    for (const file of files) {
        console.log("Procesando archivo:", file.name);

        if (file) {
            try {
                const dataToBackend = await manejarArchivoStorage(file);
                const dataFromBackend = await enviarDatosAlBackendStorage(dataToBackend);

                if (dataFromBackend.length > 0) {
                    actualizarArchivoStorage(file, dataFromBackend);
                } else {
                    console.error(`No se recibieron datos válidos del backend para ${file.name}.`);
                }
            } catch (error) {
                console.error(`Error procesando el archivo ${file.name}:`, error);
            }
        } else {
            console.error(`No se seleccionó ningún archivo.`);
        }
    }
});



async function manejarArchivoStorage(file) {
    const reader = new FileReader();

    return new Promise((resolve, reject) => {
        reader.onload = async (event) => {
            const contenido = event.target.result;
            const lineas = contenido.split(/\r?\n/);

            const datos = lineas
                .map((linea) => linea.trim())
                .filter((linea) => /^[0-9]+\s+\w+/.test(linea)) // Filtrar líneas válidas (empiezan con un número seguido de texto)
                .map((linea) => {
                    const partes = linea.split(/\s+/);
                    return partes.length >= 2
                        ? { storBin: partes[1], storUnit: partes[6] } // Devolver el valor de "Stor.bin" y "storUnit"
                        : null;
                })
                .filter(Boolean);

            resolve(datos);
        };

        reader.onerror = (error) => {
            reject("Error al leer el archivo: " + error);
        };

        reader.readAsText(file);
    });
}

async function enviarDatosAlBackendStorage(data) {
    try {
        const response = await fetch('daoAdmin/daoActualizarStorage-txt.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

        const jsonResponse = await response.json();
        //console.log("Respuesta del backend:", jsonResponse);  // Verifica la respuesta del backend
        return jsonResponse; // Devolvemos los datos procesados por el backend
    } catch (error) {
        console.error('Error enviando datos al backend:', error);
        return [];
    }
}

async function actualizarArchivoStorage(file, dataFromBackend) {
    const reader = new FileReader();
    const noMatchData = []; // Array para guardar los datos que no se encontraron

    reader.onload = async function (event) {
        const originalContent = event.target.result;
        const originalLines = originalContent.split(/\r?\n/);

        const updatedLines = originalLines.map((line) => {
            const parts = line.trim().split(/\s+/);

            if (parts.length >= 8) {
                const storBin = parts[1].trim(); // Obtener la columna Stor.bin
                const storUnit = parts[6].trim(); // Obtener la columna Storage Unit

                // Buscar coincidencia en dataFromBackend
                const matchingData = dataFromBackend.find(
                    (item) => item.storBin === storBin && item.storUnit === storUnit
                );

                if (matchingData) {
                    // Reemplazar el valor en la columna "Qty & UoM"
                    return line.replace(/____________/, matchingData.cantidad);
                } else {
                    // Si no se encontró una coincidencia, guardar los datos en noMatchData
                    noMatchData.push({ storUnit });
                }
            }

            return line; // Mantener la línea sin cambios si no hay coincidencia
        });

        // Enviar noMatchData al backend
        await handleNoMatchData(noMatchData);
    };

    reader.onerror = (error) => {
        console.error("Error al leer el archivo:", error);
    };

    reader.readAsText(file);
}

async function handleNoMatchData(noMatchData) {
    const dataFromBackendAux = await enviarDatosAlBackendStorageAux(noMatchData);
    descargarDataFromBackend(dataFromBackendAux);
}

async function enviarDatosAlBackendStorageAux(data) {
    try {
        const response = await fetch('daoAdmin/daoActualizarStorage-txtAux.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ storageUnits: data }), // Enviar todo el array de storageUnit
        });

        const jsonResponse = await response.json();
        return jsonResponse; // Devolvemos los datos procesados por el backend
    } catch (error) {
        console.error('Error enviando datos al backend:', error);
        return [];
    }
}
function descargarDataFromBackend(dataFromBackend) {
    var wb = XLSX.utils.book_new();
    wb.Props = {
        Title: "SheetJS",
        Subject: "Numeros de parte faltantes",
        Author: "Hadbetsito",
        CreatedDate: new Date()
    };
    wb.SheetNames.push("Test Sheet");
    var ws_data = [['InventoryItem', 'Record', 'Bin', 'Bin/n', 'Contador', 'Numero Parte', 'Plant','Cantidad','Sun','Type']]; // Encabezados de las columnas

    var storBinCounts = {}; // Para llevar un registro de los 'StorBin' que ya hemos visto

    for (var i = 0; i < dataFromBackend.length; i++) {
        var storageUnit = dataFromBackend[i].storageUnit;
        var inventoryItem = dataFromBackend[i].inventoryItem;
        var storage_Bin = dataFromBackend[i].storage_Bin;
        var invRecount = dataFromBackend[i].invRecount;
        var numeroParte = dataFromBackend[i].numero_Parte;
        var cantidad = dataFromBackend[i].cantidad;
        var plan = dataFromBackend[i].plan;
        var storage_Type = dataFromBackend[i].storage_Type;

        // Si 'numeroParte' está vacío, saltar esta iteración
        if (!numeroParte) {
            continue;
        }

        // Si 'StorBin' no comienza con 'P', añadir un contador al final
        var storage_Bin_Modified = storage_Bin;
        if (!storage_Bin.startsWith('P')) {
            storage_Bin_Modified = storage_Bin + '/' + (storBinCounts[storage_Bin] || 1);
            storBinCounts[storage_Bin] = (storBinCounts[storage_Bin] || 0) + 1;
        }

        ws_data.push([inventoryItem, invRecount, storage_Bin, storage_Bin+"/"+storBinCounts[storage_Bin], storBinCounts[storage_Bin], numeroParte, plan, cantidad,storageUnit, storage_Type]);
    }

    var ws = XLSX.utils.aoa_to_sheet(ws_data);
    wb.Sheets["Test Sheet"] = ws;
    var wbout = XLSX.write(wb, {bookType:'xlsx',  type: 'binary'});

    function s2ab(s) {
        var buf = new ArrayBuffer(s.length);
        var view = new Uint8Array(buf);
        for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
        return buf;
    }

    saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), 'Numeros de parte faltantes.xlsx');
}
