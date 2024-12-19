document.getElementById('btnExcelExcelQty').addEventListener('click', () => {
    document.getElementById('fileInputExcelQty').click();
});

document.getElementById('fileInputExcelQty').addEventListener('change', async (event) => {
    const file = event.target.files[0]; // El archivo seleccionado
    console.log("Archivo seleccionado:", file); // Verifica el archivo seleccionado

    if (file) {
        try {
            // Paso 1: Procesar el archivo Excel
            const dataToBackend = await manejarExcelCompleto(file);

            // Paso 2: Enviar los datos al PHP
            const responseFromPHP = await enviarDatosAPHP(dataToBackend);
            console.log("Respuesta del PHP:", responseFromPHP);

            // Paso 3: Consultar el backend con los datos extraídos
            const dataFromBackend = await buscarValoresEnBaseDeDatos(dataToBackend);

            // Paso 4: Actualizar el archivo Excel solo si hay datos del backend
            if (dataFromBackend.length > 0) {
                await actualizarExcelQty(file, dataFromBackend);
                console.log("Archivo Excel actualizado y descargado.");
            } else {
                console.error("No se recibieron datos válidos del backend.");
            }
        } catch (error) {
            console.error("Ocurrió un error durante el proceso:", error);
        }
    } else {
        console.error("No se seleccionó ningún archivo.");
    }
});

async function manejarExcelCompleto(file) {
    const workbook = new ExcelJS.Workbook();
    const data = await file.arrayBuffer();
    await workbook.xlsx.load(data);

    const worksheet = workbook.getWorksheet(1); // Primera hoja
    const ExcelQtyData = [];

    worksheet.eachRow((row, rowNumber) => {
        if (rowNumber > 1) { // Omitir encabezados
            const registro = {
                Client: row.getCell(1).value || "",
                WarehouseNo: row.getCell(2).value || "",
                InventoryItem: row.getCell(3).value || "",
                Quant: row.getCell(5).value || "",
                InvRecount: row.getCell(6).value || "",
                InventStatus: row.getCell(7).value || "",
                InventoryPage: row.getCell(9).value || "",
                StorageType: row.getCell(10).value || "",
                StorageBin: row.getCell(11).value || "",
                BinPosition: row.getCell(12).value || "",
                Material: row.getCell(13).value || "",
                Plant: row.getCell(14).value || "",
                Batch: row.getCell(15).value || "",
                StorUnitType: row.getCell(34).value || "",
                TotalStock: row.getCell(28).value || "",
                Invent: row.getCell(30).value || "",
                TransferOrder: row.getCell(17).value || "",
                TransferItem: row.getCell(32).value || "",
                StorageLocation: row.getCell(33).value || "",
                NameCounter: row.getCell(37).value || ""
            };
            ExcelQtyData.push(registro);
        }
    });

    return ExcelQtyData;
}

async function enviarDatosAPHP(datos) {
    try {
        const response = await fetch('daoAdmin/daoInsertarInv.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ bitacoraDatos: datos }),
        });

        if (!response.ok) {
            throw new Error(`Error al consultar el backend: ${response.statusText}`);
        }

        // El backend devuelve un array con las columnas adicionales (M y N)
        const resultados = await response.json();

        // Verificar si hubo un error en el backend
        if (resultados.status === 'error') {
            console.error("Error en el backend:", resultados.message);
            // Aquí puedes manejar el error como prefieras, por ejemplo, mostrando un mensaje al usuario
        }

        return resultados;
    } catch (error) {
        console.error("Error en la consulta al backend:", error);
        return [];
    }
}

async function manejarExcelQty(file) {
    const workbook = new ExcelJS.Workbook();
    const data = await file.arrayBuffer();
    await workbook.xlsx.load(data);

    const worksheet = workbook.getWorksheet(1); // Primera hoja
    const ExcelQtyData = [];

    worksheet.eachRow((row, rowNumber) => {
        if (rowNumber > 1) { // Omitir encabezados
            const registro = {
                storageBin: row.getCell(11).value || "", // Columna G
                noParte: row.getCell(13).value || "", // Columna I
                storageUnit: row.getCell(16).value || "" // Columna L
            };
            ExcelQtyData.push(registro);
        }
    });

    return ExcelQtyData;
}

async function buscarValoresEnBaseDeDatos(datos) {
    try {
        const response = await fetch('daoAdmin/daoActualizarExcelQty.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(datos),
        });

        if (!response.ok) {
            throw new Error(`Error al consultar el backend: ${response.statusText}`);
        }

        // El backend devuelve un array con las columnas adicionales (M y N)
        const resultados = await response.json();
        return resultados;
    } catch (error) {
        console.error("Error en la consulta al backend:", error);
        return [];
    }
}

async function actualizarExcelQty(file, dataFromBackend) {
    const workbook = new ExcelJS.Workbook();
    const data = await file.arrayBuffer();  // Leemos el archivo
    await workbook.xlsx.load(data);  // Cargamos el archivo Excel en el workbook

    console.log("Archivo Excel cargado correctamente.");

    const worksheet = workbook.getWorksheet(1); // Suponiendo que trabajas con la primera hoja

    // Recorremos las filas del Excel, excluyendo el encabezado
    worksheet.eachRow((row, rowNumber) => {
        if (rowNumber > 1) { // Excluir la primera fila (encabezados)
            const storageBin = row.getCell(11).value?.toString().trim(); // Columna G es storageBin
            const materialNo = row.getCell(13).value?.toString().trim();  // Columna I es materialNo
            const storageUnit = row.getCell(34).value?.toString().trim();  // Columna L es storageUnit

            console.log(`Procesando fila ${rowNumber}: storageBin = ${storageBin}, materialNo = ${materialNo}, storageUnit = ${storageUnit}`);

            // Realizar la distinción por storageUnit
            let matchingData;
            if (storageUnit && storageUnit !== '') {
                console.log(`Buscando por storageUnit: ${storageUnit}`);
                matchingData = dataFromBackend.find(
                    (item) => item.storageUnit === storageUnit
                );
            } else {
                console.log(`Buscando por storageBin: ${storageBin}, materialNo: ${materialNo}`);
                // Buscar coincidencia en los datos del backend por storageBin y materialNo
                matchingData = dataFromBackend.find(
                    (item) => item.storageBin === storageBin && item.noParte === materialNo
                );
            }

            if (matchingData) {
                console.log(`Coincidencia encontrada: storageBin = ${storageBin}, materialNo = ${materialNo}`);
                console.log(`Actualizando columnas L y M con: storageUnit = ${matchingData.storageUnit}, cantidad = ${matchingData.cantidad}`);

                // Si hay coincidencia, actualizamos las celdas correspondientes
                row.getCell(18).value = matchingData.cantidad;     // Columna M - cantidad
                row.getCell(18).value = matchingData.unit;  // Columna N - unit
            } else {
                //console.log(`No se encontró coincidencia para storageBin: ${storageBin}, materialNo: ${materialNo}, storageUnit: ${storageUnit}`);
            }
        }
    });

    // Guardar el archivo actualizado
    const blob = await workbook.xlsx.writeBuffer();
    console.log("Archivo actualizado preparado para descarga.");

    const a = document.createElement('a');
    a.href = URL.createObjectURL(new Blob([blob]));
    a.download = `Actualizado_${file.name}`; // Nombre del archivo descargado
    a.click();

    console.log("Descarga del archivo iniciada.");
    await numerosFaltantes();
}

async function numerosFaltantes() {
    $.getJSON('https://grammermx.com/Logistica/Inventario2024/dao/consultaInv.php', function (data) {
        if (data && data.data && data.data.length > 0) {
            Swal.fire({
                title: "Se encontraron numeros de parte que no estan en el archivo pero si en la base de datos",
                text: "Se te descargara un archivo",
                icon: "info"
            });
            var wb = XLSX.utils.book_new();
            wb.Props = {
                Title: "SheetJS",
                Subject: "Numeros de parte faltantes",
                Author: "Hadbetsito",
                CreatedDate: new Date(2017,12,19)
            };
            wb.SheetNames.push("Test Sheet");
            var storageBinCount = {};
            var ws_data = [];

            for (var i = 0; i < data.data.length; i++) {
                var InventoryItem = data.data[i].InventoryItem;
                var StorageBin = data.data[i].StorageBin;
                var StorageBinCompleto = data.data[i].StorageBin;
                var NumeroParte = data.data[i].NumeroParte;
                var Plant = data.data[i].Plant;
                var Cantidad = data.data[i].Cantidad;
                var StorageUnit = data.data[i].StorageUnit;
                var StorageType = data.data[i].StorageType;
                var InvRecount = data.data[i].InvRecount; // Assuming you have InvRecount in your data

                // Add a consecutive number to StorageBin if it's a duplicate and starts with 'R'
                var StorageBinNumber = '';
                if (StorageBin.startsWith('R')) {
                    if (storageBinCount[StorageBin]) {
                        storageBinCount[StorageBin]++;
                    } else {
                        storageBinCount[StorageBin] = 1;
                    }
                    StorageBinNumber = storageBinCount[StorageBin];
                    StorageBin = StorageBin + '/' + StorageBinNumber;
                }

                ws_data.push([InventoryItem, InvRecount, StorageBin,StorageBinNumber, StorageBinCompleto, NumeroParte, Plant, Cantidad, StorageUnit, StorageType]);
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
    });
}






/**********************************************************************************************************************/
/********************************************************TOOLTIPS******************************************************/
/**********************************************************************************************************************/

function mostrarImagenTooltip(idTooltip, imageUrl, width, height) {
    const tooltip = document.getElementById(idTooltip);

    // Configuración de Tippy.js
    tippy(tooltip, {
        trigger: 'click', // Mostrar el tooltip al hacer clic
        animation: 'shift-away',
        theme: 'light',
        arrow: true, // Mostrar flecha en el tooltip
        allowHTML: true, // Permitir contenido HTML dentro del tooltip
        onShow(instance) {
            // Crear una estructura HTML personalizada
            const container = document.createElement('div');
            container.style.display = 'flex';
            container.style.flexDirection = 'column';
            container.style.alignItems = 'center';
            container.style.padding = '10px';
            container.style.backgroundColor = '#fff';
            container.style.borderRadius = '8px';
            container.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.2)';
            container.style.width = `${width}px`;
            container.style.height = `${height}px`;

            // Crear el elemento de imagen
            const image = new Image();
            image.src = imageUrl;
            image.style.width = '100%'; // Ajustar al ancho del contenedor
            image.style.height = '100%'; // Ajustar al alto del contenedor
            image.style.objectFit = 'contain'; // Escalar la imagen para que no se deforme
            image.style.borderRadius = '5px';

            // Agregar la imagen al contenedor
            container.appendChild(image);

            // Asignar el contenedor al contenido del tooltip
            instance.setContent(container);
        },
    });
}


/**********************************************************************************************************************/
/**************************************************TABLA BITACORA******************************************************/
/**********************************************************************************************************************/

function cargarDatosBitacora() {
    fetch('daoAdmin/daoConsultarBitacora.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('bodyBitacora');
            tableBody.innerHTML = ''; // Limpiar el contenido anterior

            // Verificar si hay datos en "data"
            if (data && data.data) {
                data.data.forEach(bitacora => {
                    const row = document.createElement('tr');

                    // Crear celdas para cada columna
                    row.innerHTML = `
                            <td>${bitacora.Id_Bitacora}</td>
                            <td>${bitacora.NumeroParte}</td>
                            <td>${bitacora.FolioMarbete}</td>
                            <td>${bitacora.Fecha}</td>
                            <td>${bitacora.Usuario}</td>
                            <td>${bitacora.Estatus}</td>
                            <td>${bitacora.PrimerConteo}</td>
                            <td>${bitacora.SegundoConteo}</td>
                            <td>${bitacora.TercerConteo}</td>
                            <td>${bitacora.Comentario}</td>
                            <td>${bitacora.StorageBin}</td>
                            <td>${bitacora.StorageType}</td>
                            <td>${bitacora.Area}</td>
                        `;

                    // Agregar la fila a la tabla
                    tableBody.appendChild(row);
                });
            } else {
                // Si no hay datos, mostrar un mensaje en la tabla
                const row = document.createElement('tr');
                row.innerHTML = '<td colspan="13" class="text-center">No hay datos disponibles</td>';
                tableBody.appendChild(row);
            }
        })
        .catch(error => {
            console.error('Error al cargar los datos:', error);
        });
}
/******************Cargar e insertar datos de Excel*******************/
document.getElementById('btnExcelBitacora').addEventListener('click', () => {
    document.getElementById('fileInputBitacora').click();
});

document.getElementById('fileInputBitacora').addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
        insertarExcelBitacora(file);
    }
});

async function insertarExcelBitacora(file) {
    try {
        // Leer el archivo Excel
        const data = await file.arrayBuffer();
        const workbook = XLSX.read(data, { type: 'array' });
        const worksheet = workbook.Sheets[workbook.SheetNames[0]];

        // Obtener el rango del Excel
        const range = XLSX.utils.decode_range(worksheet['!ref']);

        // Crear un array para almacenar los datos
        const bitacoraData = [];

        // Recorrer fila por fila, asegurándose de incluir todas las columnas (A, B, C, D, E)
        for (let row = range.s.r; row <= range.e.r; row++) {
            const registro = {
                NumeroParte: worksheet[`A${row + 1}`]?.v || "", // Columna A
                FolioMarbete: worksheet[`B${row + 1}`]?.v || "", // Columna B
                StorageBin: worksheet[`C${row + 1}`]?.v || "", // Columna C
                StorageType: worksheet[`D${row + 1}`]?.v || "", // Columna D
                Area: worksheet[`E${row + 1}`]?.v || "" // Columna E
            };
            bitacoraData.push(registro);
        }

        // Enviar los datos al backend
        const response = await fetch('daoAdmin/daoInsertarBitacora.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ bitacoraDatos: bitacoraData })
        });

        // Obtener la respuesta del backend
        const result = await response.json();

        if (result.status === "success") {
            Swal.fire({
                icon: 'success',
                title: 'Actualización exitosa',
                text: result.message
            });

            cargarDatosBitacora();
        } else {
            throw new Error(result.message + ' Detalles: ' + result.detalles);
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Ocurrió un error al procesar el archivo. Recargue la página e intente nuevamente.'
        });
    }
}

/**********************************************************************************************************************/
/********************************************************TABLA AREA****************************************************/
/**********************************************************************************************************************/
function cargarDatosArea() {
    fetch('daoAdmin/daoConsultarArea.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('bodyArea');
            tableBody.innerHTML = ''; // Limpiar el contenido anterior

            // Verificar si hay datos en "data"
            if (data && data.data) {
                data.data.forEach(area => {
                    const row = document.createElement('tr');

                    // Crear celdas para cada columna
                    row.innerHTML = `
                            <td>${area.IdArea}</td>
                            <td>${area.AreaNombre}</td>
                            <td>${area.AreaProduccion}</td>
                            <td>${area.StLocation}</td>
                            <td>${area.StBin}</td>
                        `;

                    // Agregar la fila a la tabla
                    tableBody.appendChild(row);
                });
            } else {
                // Si no hay datos, mostrar un mensaje en la tabla
                const row = document.createElement('tr');
                row.innerHTML = '<td colspan="5" class="text-center">No hay datos disponibles</td>';
                tableBody.appendChild(row);
            }
        })
        .catch(error => {
            console.error('Error al cargar los datos:', error);
        });
}
/******************Cargar e insertar datos de Excel*******************/
document.getElementById('btnExcelArea').addEventListener('click', () => {
    document.getElementById('fileInputArea').click();
});

document.getElementById('fileInputArea').addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
        insertarExcelArea(file);
    }
});
async function insertarExcelArea(file) {
    try {
        // Leer el archivo Excel
        const data = await file.arrayBuffer();
        const workbook = XLSX.read(data, { type: 'array' });
        const worksheet = workbook.Sheets[workbook.SheetNames[0]];
        const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

        // Mapear los datos, asegurándonos de convertir las fechas correctamente
        const areaData = jsonData.slice(1).map((row) => {
            return {
                AreaNombre: row[0],
                AreaProduccion: row[1],
                StLocation: row[2],
                StBin: row [3]
            };
        });

        // Enviar los datos al backend
        const response = await fetch('daoAdmin/daoInsertarArea.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ areaDatos: areaData })
        });

        // Obtener la respuesta del backend
        const result = await response.json();

        if (result.status === "success") {
            Swal.fire({
                icon: 'success',
                title: 'Actualización exitosa',
                text: result.message
            });

            cargarDatosArea();
        } else {
            // Mostrar el mensaje de error que viene del backend
            throw new Error(result.message );
        }

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Ocurrió un error al procesar el archivo. Recargue la página e intente nuevamente.'
        });
    }
}
/**********************************************************************************************************************/
/********************************************************TABLA UBICAIONES***************************************************/
/**********************************************************************************************************************/
function cargarDatosUbicaciones() {
    fetch('daoAdmin/daoConsultarUbicaciones.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('bodyUbicaciones');
            tableBody.innerHTML = ''; // Limpiar el contenido anterior

            // Verificar si hay datos en "data"
            if (data && data.data) {
                data.data.forEach(ubicaciones => {
                    const row = document.createElement('tr');

                    // Crear celdas para cada columna
                    row.innerHTML = `
                            <td>${ubicaciones.GrammerNo}</td>
                            <td>${ubicaciones.PVB}</td>
                        `;

                    // Agregar la fila a la tabla
                    tableBody.appendChild(row);
                });
            } else {
                // Si no hay datos, mostrar un mensaje en la tabla
                const row = document.createElement('tr');
                row.innerHTML = '<td colspan="2" class="text-center">No hay datos disponibles</td>';
                tableBody.appendChild(row);
            }
        })
        .catch(error => {
            console.error('Error al cargar los datos:', error);
        });
}
/******************Cargar e insertar datos de Excel*******************/
document.getElementById('btnExcelUbicaciones').addEventListener('click', () => {
    document.getElementById('fileInputUbicaciones').click();
});

document.getElementById('fileInputUbicaciones').addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
        insertarExcelUbicaciones(file);
    }
});
async function insertarExcelUbicaciones(file) {
    try {
        // Leer el archivo Excel
        const data = await file.arrayBuffer();
        const workbook = XLSX.read(data, { type: 'array' });
        const worksheet = workbook.Sheets[workbook.SheetNames[0]];
        const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

        // Mapear los datos, asegurándonos de convertir las fechas correctamente
        const UbicacionesData = jsonData.slice(1).map((row) => {
            return {
                GrammerNo: row[0],
                PVB: row[1]
            };
        });

        // Enviar los datos al backend
        const response = await fetch('daoAdmin/daoInsertarUbicaciones.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ ubicacionesDatos: UbicacionesData })
        });

        // Obtener la respuesta del backend
        const result = await response.json();

        if (result.status === "success") {
            Swal.fire({
                icon: 'success',
                title: 'Actualización exitosa',
                text: result.message
            });

            cargarDatosUbicaciones();
        } else {
            // Mostrar el mensaje de error que viene del backend
            throw new Error(result.message );
        }

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Ocurrió un error al procesar el archivo. Recargue la página e intente nuevamente.'
        });
    }
}

/**********************************************************************************************************************/
/********************************************************TABLA STORAGE***************************************************/
/**********************************************************************************************************************/
function cargarDatosStorage() {
    fetch('daoAdmin/daoConsultarStorage.php')
        .then(response => response.json())
        .then(data => {
            console.log('Datos recibidos:', data); // Mensaje de depuración para ver los datos completos

            const tableBody = document.getElementById('bodyStorage');
            tableBody.innerHTML = ''; // Limpiar el contenido anterior

            // Verificar si hay datos en "data"
            if (data && data.data) {
                console.log('Datos disponibles en "data.data":', data.data); // Verifica si hay datos en data.data
                data.data.forEach(storage => {
                    console.log('Procesando storage:', storage); // Verifica cada elemento procesado

                    const row = document.createElement('tr');

                    // Crear celdas para cada columna
                    row.innerHTML = `
                        <td>${storage.id_StorageUnit}</td>
                        <td>${storage.Numero_Parte}</td>
                        <td>${storage.Cantidad}</td>
                        <td>${storage.Storage_Bin}</td>
                        <td>${storage.Storage_Type}</td>
                    `;

                    // Agregar la fila a la tabla
                    tableBody.appendChild(row);
                });
            } else {
                console.log('No hay datos disponibles'); // Mensaje de depuración cuando no hay datos
                // Si no hay datos, mostrar un mensaje en la tabla
                const row = document.createElement('tr');
                row.innerHTML = '<td colspan="5" class="text-center">No hay datos disponibles</td>';
                tableBody.appendChild(row);
            }
        })
        .catch(error => {
            console.error('Error al cargar los datos:', error); // Captura y muestra el error en consola
        });
}

/******************Cargar e insertar datos de Excel*******************/
document.getElementById('btnExcelStorage').addEventListener('click', () => {
    document.getElementById('fileInputStorage').click();
});

document.getElementById('fileInputStorage').addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
        insertarExcelStorage(file);
    }
});
async function insertarExcelStorage(file) {
    try {
        // Leer el archivo Excel
        const data = await file.arrayBuffer();
        const workbook = XLSX.read(data, { type: 'array' });
        const worksheet = workbook.Sheets[workbook.SheetNames[0]];
        const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

        // Mapear los datos, asegurándonos de convertir las fechas correctamente
        const storageData = jsonData.slice(1).map((row) => {
            return {
                id_StorageUnit: row[0],
                Numero_Parte: row[1],
                Cantidad: row[2],
                Storage_Bin: row[3],
                Storage_Type: row[4]
            };
        });

        // Enviar los datos al backend
        const response = await fetch('daoAdmin/daoInsertarStorage.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ storageDatos: storageData })
        });

        // Obtener la respuesta del backend
        const result = await response.json();

        if (result.status === "success") {
            Swal.fire({
                icon: 'success',
                title: 'Actualización exitosa',
                text: result.message
            });

            cargarDatosStorage();
        } else {
            // Mostrar el mensaje de error que viene del backend
            throw new Error(result.message );
        }

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Ocurrió un error al procesar el archivo. Recargue la página e intente nuevamente.'
        });
    }
}


/**********************************************************************************************************************/
/********************************************************TABLA INVENTARIO***************************************************/
/**********************************************************************************************************************/

function cargarDatosInventario() {
    fetch('daoAdmin/daoConsultarInventario.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('bodyInventario');
            tableBody.innerHTML = ''; // Limpiar el contenido anterior

            // Verificar si hay datos en "data"
            if (data && data.data) {
                data.data.forEach(inventario => {
                    const row = document.createElement('tr');

                    // Crear celdas para cada columna
                    row.innerHTML = `
                            <td>${inventario.STLocation}</td>
                            <td>${inventario.StBin}</td>
                            <td>${inventario.StType}</td>
                            <td>${inventario.GrammerNo}</td>
                            <td>${inventario.Cantidad}</td>
                            <td>${inventario.AreaCve}</td>
                        `;

                    // Agregar la fila a la tabla
                    tableBody.appendChild(row);
                });
            } else {
                // Si no hay datos, mostrar un mensaje en la tabla
                const row = document.createElement('tr');
                row.innerHTML = '<td colspan="6" class="text-center">No hay datos disponibles</td>';
                tableBody.appendChild(row);
            }
        })
        .catch(error => {
            console.error('Error al cargar los datos:', error);
        });
}

/******************Cargar e insertar datos de Excel*******************/
document.getElementById('btnExcelInventario').addEventListener('click', () => {
    document.getElementById('fileInputInventario').click();
});

document.getElementById('fileInputInventario').addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
        insertarExcelInventario(file);
    }
});
async function insertarExcelInventario(file) {
    try {
        // Leer el archivo Excel
        const data = await file.arrayBuffer();
        const workbook = XLSX.read(data, { type: 'array' });
        const worksheet = workbook.Sheets[workbook.SheetNames[0]];
        const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

        // Mapear los datos, asegurándonos de convertir las fechas correctamente
        const inventarioData = jsonData.slice(1).map((row) => {
            return {
                STLocation: row[0],
                StBin: row[1],
                StType: row[2],
                GrammerNo: row[3],
                Cantidad: row[4],
                AreaCve: row[5]
            };
        });

        // Enviar los datos al backend
        const response = await fetch('daoAdmin/daoInsertarInventario.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ inventarioDatos: inventarioData })
        });

        // Obtener la respuesta del backend
        const result = await response.json();

        if (result.status === "success") {
            Swal.fire({
                icon: 'success',
                title: 'Actualización exitosa',
                text: result.message
            });

            cargarDatosInventario();
        } else {
            // Mostrar el mensaje de error que viene del backend
            throw new Error(result.message );
        }

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Ocurrió un error al procesar el archivo. Recargue la página e intente nuevamente.'
        });
    }
}


/**********************************************************************************************************************/
/********************************************************TABLA BIN***************************************************/
/**********************************************************************************************************************/
function cargarDatosBin() {
    fetch('daoAdmin/daoConsultarBin.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('bodyBin');
            tableBody.innerHTML = ''; // Limpiar el contenido anterior

            // Verificar si hay datos en "data" y que "data.data" sea un array
            if (data && Array.isArray(data.data) && data.data.length > 0) {
                data.data.forEach(bin => {
                    const row = document.createElement('tr');

                    // Crear celdas para cada columna usando createElement
                    const cell1 = document.createElement('td');
                    cell1.textContent = bin.StBin;
                    row.appendChild(cell1);

                    const cell2 = document.createElement('td');
                    cell2.textContent = bin.StType;
                    row.appendChild(cell2);

                    // Agregar la fila a la tabla
                    tableBody.appendChild(row);
                });
            } else {
                // Si no hay datos, mostrar un mensaje en la tabla
                const row = document.createElement('tr');
                const cell = document.createElement('td');
                cell.colSpan = 2;
                cell.classList.add('text-center');
                cell.textContent = 'No hay datos disponibles';
                row.appendChild(cell);
                tableBody.appendChild(row);
            }
        })
        .catch(error => {
            console.error('Error al cargar los datos:', error);
        });
}

/******************Cargar e insertar datos de Excel*******************/
document.getElementById('btnExcelBin').addEventListener('click', () => {
    document.getElementById('fileInputBin').click();
});

document.getElementById('fileInputBin').addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
        insertarExcelBin(file);
    }
});
async function insertarExcelBin(file) {
    try {
        // Leer el archivo Excel
        const data = await file.arrayBuffer();
        const workbook = XLSX.read(data, { type: 'array' });
        const worksheet = workbook.Sheets[workbook.SheetNames[0]];
        const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

        // Mapear los datos, asegurándonos de convertir las fechas correctamente
        const binData = jsonData.slice(1).map((row) => {
            return {
                StBin: row[0],
                StType: row[1]
            };
        });

        // Enviar los datos al backend
        const response = await fetch('daoAdmin/daoInsertarBin.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ binDatos: binData })
        });

        // Obtener la respuesta del backend
        const result = await response.json();

        if (result.status === "success") {
            Swal.fire({
                icon: 'success',
                title: 'Actualización exitosa',
                text: result.message
            });

            cargarDatosBin();
        } else {
            // Mostrar el mensaje de error que viene del backend
            throw new Error(result.message );
        }

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Ocurrió un error al procesar el archivo. Recargue la página e intente nuevamente.'
        });
    }
}


/**********************************************************************************************************************/
/********************************************************TABLA PARTE***************************************************/
/**********************************************************************************************************************/
function cargarDatosParte() {
    fetch('daoAdmin/daoConsultarParte.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('bodyParte');
            tableBody.innerHTML = ''; // Limpiar el contenido anterior

            // Verificar si hay datos en "data"
            if (data && data.data) {
                data.data.forEach(parte => {
                    const row = document.createElement('tr');

                    // Crear celdas para cada columna
                    row.innerHTML = `
                            <td>${parte.GrammerNo}</td>
                            <td>${parte.Descripcion}</td>
                            <td>${parte.UM}</td>
                            <td>${parte.ProfitCtr}</td>
                            <td>${parte.Costo}</td>
                            <td>${parte.Por}</td>
                        `;

                    // Agregar la fila a la tabla
                    tableBody.appendChild(row);
                });
            } else {
                // Si no hay datos, mostrar un mensaje en la tabla
                const row = document.createElement('tr');
                row.innerHTML = '<td colspan="6" class="text-center">No hay datos disponibles</td>';
                tableBody.appendChild(row);
            }
        })
        .catch(error => {
            console.error('Error al cargar los datos:', error);
        });
}
/******************Cargar e insertar datos de Excel*******************/
document.getElementById('btnExcelParte').addEventListener('click', () => {
    document.getElementById('fileInputParte').click();
});

document.getElementById('fileInputParte').addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
        insertarExcelParte(file);
    }
});
async function insertarExcelParte(file) {
    try {
        // Leer el archivo Excel
        const data = await file.arrayBuffer();
        const workbook = XLSX.read(data, { type: 'array' });
        const worksheet = workbook.Sheets[workbook.SheetNames[0]];
        const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

        // Mapear los datos, asegurándonos de convertir las fechas correctamente
        const parteData = jsonData.slice(1).map((row) => {
            return {
                GrammerNo: row[0],
                Descripcion: row[1],
                UM: row[2],
                ProfitCtr: row[3],
                Costo: row[4],
                Por: row[5]
            };
        });

        // Enviar los datos al backend
        const response = await fetch('daoAdmin/daoInsertarParte.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ parteDatos: parteData })
        });

        // Obtener la respuesta del backend
        const result = await response.json();

        if (result.status === "success") {
            Swal.fire({
                icon: 'success',
                title: 'Actualización exitosa',
                text: result.message
            });

            cargarDatosParte();
        } else {
            // Mostrar el mensaje de error que viene del backend
            throw new Error(result.message );
        }

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Ocurrió un error al procesar el archivo. Recargue la página e intente nuevamente.'
        });
    }
}
