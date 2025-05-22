<!DOCTYPE html>
<html>
<head>
    <title>Imprimir Ticket</title>
    <script src="https://cdn.jsdelivr.net/npm/qz-tray@2.1.0/qz-tray.js"></script>
</head>
<body>
    <h2>Vista previa del ticket</h2>
    <pre id="preview">{{ $ticket }}</pre>
    <button onclick="imprimirTicket()">Imprimir en comandera</button>
    <button onclick="listarImpresoras()">Ver impresoras detectadas</button>
    <select id="impresora"></select>
    <script>
    let impresoraPorDefecto = "";

    function listarImpresoras() {
        qz.websocket.connect().then(() => {
            return qz.printers.findAll();
        }).then(printers => {
            let select = document.getElementById("impresora");
            select.innerHTML = "";
            printers.forEach(p => {
                let opt = document.createElement("option");
                opt.value = p;
                opt.text = p;
                select.appendChild(opt);
            });
            if (printers.length > 0) {
                impresoraPorDefecto = printers[0];
            }
            alert("Impresoras detectadas:\n" + printers.join("\n"));
        }).catch(e => alert("No se pudo listar impresoras: " + e));
    }

    function imprimirTicket() {
        // El ticket viene ya limpio, solo necesitas base64 para evitar problemas de codificación
        const ticket = atob("{{ base64_encode($ticket) }}");
        let impresora = impresoraPorDefecto;
        let select = document.getElementById("impresora");
        if (select && select.value) {
            impresora = select.value;
        }
        if (!impresora) {
            alert("Selecciona una impresora primero.");
            return;
        }
        qz.websocket.connect()
        .then(() => qz.printers.find(impresora))
        .then(printer => {
            let config = qz.configs.create(printer);
            let data = [
                { type: 'raw', format: 'plain', data: ticket }
            ];
            return qz.print(config, data);
        })
        .then(() => alert("¡Ticket enviado a la impresora!"))
        .catch(e => alert("¡Error al imprimir! " + e));
    }

    window.onload = listarImpresoras;
    </script>
</body>
</html>