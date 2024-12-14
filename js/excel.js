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
