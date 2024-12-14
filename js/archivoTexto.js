/**********************************************************************************************************************/
/********************************************************TABLA BITACORA***********************************************/
/**********************************************************************************************************************/

document.getElementById('btnTxtBitacora').addEventListener('click', () => {
    document.getElementById('fileInputTxt').click();
});

document.getElementById('fileInputTxt').addEventListener('change', async (event) => {
    const file = event.target.files[0]; // El archivo seleccionado
    console.log("Archivo seleccionado:", file);  // Agrega esto para verificar el archivo

    if (file) {
        // Procesar el archivo y enviar los datos al backend
        const dataToBackend = await manejarArchivo(file);
        const dataFromBackend = await enviarDatosAlBackend(dataToBackend);

        if (dataFromBackend.length > 0) {
            // Solo actualiza si dataFromBackend tiene datos
            actualizarContenidoArchivo(file, dataFromBackend);
        } else {
            console.error("No se recibieron datos válidos del backend.");
        }
    } else {
        console.error("No se seleccionó ningún archivo.");
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

        //console.log("Contenido original del archivo:");
        //console.log(originalContent);

        const updatedLines = originalLines.map((line) => {
            // Divide la línea en partes basándose en espacios/tabulaciones
            const parts = line.trim().split(/\s+/);

            if (parts.length >= 6) {
                const storBin = parts[1]; // `storBin` es el segundo elemento
                const materialNo = parts[5]; // `materialNo` es el sexto elemento

                //console.log(`Procesando línea: ${line}`);
                //console.log(`Extracted storBin: ${storBin}, materialNo: ${materialNo}`);

                // Buscar coincidencia en dataFromBackend
                const matchingData = dataFromBackend.find(
                    (item) => item.storBin === storBin && item.materialNo === materialNo
                );

                if (matchingData) {
                    //console.log(`Coincidencia encontrada para storBin: ${storBin}, materialNo: ${materialNo}`);
                    //console.log(`Reemplazando ______________ con: ${matchingData.conteoFinal}`);
                    return line.replace("______________", matchingData.conteoFinal);
                } else {
                    //console.log(`No se encontró coincidencia para storBin: ${storBin}, materialNo: ${materialNo}`);
                }
            } else {
                //console.log("Formato de línea inesperado:", line);
            }

            return line; // Mantener la línea sin cambios si no hay coincidencia
        });

        //console.log("Contenido actualizado del archivo:");
        //console.log(updatedLines.join("\n")); // Verifica el contenido final

        const finalContent = updatedLines.join("\n"); // Unir las líneas actualizadas
        const blob = new Blob([finalContent], { type: "text/plain" });

        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = `actualizado_${file.name}`;
        link.click();
    };

    //console.log("Datos del backend recibidos:");
    //console.log(JSON.stringify(dataFromBackend, null, 2)); // Verifica los datos recibidos del backend

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

/**********************************************************************************************************************/
/*****************************************************TABLA STORAGE_UNIT***********************************************/
/**********************************************************************************************************************/

document.getElementById('btnTxtStorage').addEventListener('click', () => {
    document.getElementById('fileInputTxtS').click();
});

document.getElementById('fileInputTxtS').addEventListener('change', async (event) => {
    const file = event.target.files[0]; // El archivo seleccionado
    console.log("Archivo seleccionado:", file);  // Verifica el archivo seleccionado

    if (file) {
        // Procesar el archivo y enviar los datos al backend
        const dataToBackend = await manejarArchivoStorage(file);
        const dataFromBackend = await enviarDatosAlBackendStorage(dataToBackend);

        if (dataFromBackend.length > 0) {
            // Solo actualiza si dataFromBackend tiene datos
            actualizarArchivoStorage(file, dataFromBackend);
        } else {
            console.error("No se recibieron datos válidos del backend.");
        }
    } else {
        console.error("No se seleccionó ningún archivo.");
    }
});

async function manejarArchivoStorage(file) {
    const reader = new FileReader();

    return new Promise((resolve, reject) => {
        reader.onload = async (event) => {
            const contenido = event.target.result;
            // Dividir las líneas del archivo
            const lineas = contenido.split(/\r?\n/);

            //console.log("Contenido original del archivo:");
            //console.log(contenido);

            // Filtrar las líneas que contienen datos válidos
            const datos= lineas
                .map((linea) => linea.trim())
                .filter((linea) => /^[0-9]+\s+\w+/.test(linea)) // Filtrar líneas válidas (empiezan con un número seguido de texto)
                .map((linea) => {
                    // Separar los datos de cada línea
                    const partes = linea.split(/\s+/);

                    return partes.length >= 7
                        ? { storUnit: partes[6] }
                        : null;
                })
                .filter(Boolean); // Eliminar entradas nulas

            //console.log("Datos procesados desde el archivo:", datos);  // Verifica los datos procesados

            // Resolvemos la promesa con los datos procesados
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

    reader.onload = function (event) {
        const originalContent = event.target.result;
        const originalLines = originalContent.split(/\r?\n/); // Divide el archivo en líneas

        //console.log("Contenido original del archivo:");
        //console.log(originalContent);

        const updatedLines = originalLines.map((line) => {
            // Divide la línea en partes basándose en espacios/tabulaciones
            const parts = line.trim().split(/\s+/); // Separar por espacios múltiples

            if (parts.length >= 8) { // Verificar que haya suficientes columnas
                const storageUnit = parts[6].trim(); // Obtener la columna Storage Unit

                //console.log(`Procesando línea: ${line}`);
                //console.log(`Extracted storageUnit: ${storageUnit}`);

                // Buscar coincidencia en dataFromBackend
                const matchingData = dataFromBackend.find(
                    (item) => item.storageUnit === storageUnit
                );

                if (matchingData) {
                    //console.log(`Coincidencia encontrada para storageUnit: ${storageUnit}`);
                    //console.log(`Reemplazando ______________ con: ${matchingData.cantidad}`);
                    // Reemplazar el valor en la columna "Qty & UoM"
                    return line.replace(/____________/, matchingData.cantidad);
                }else {
                    //console.log(`No se encontró coincidencia para storageUnit: ${storageUnit}`);
                }
            }else {
                //console.log("Formato de línea inesperado:", line);
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

    reader.onerror = (error) => {
        console.error("Error al leer el archivo:", error);
    };

    reader.readAsText(file);
}
