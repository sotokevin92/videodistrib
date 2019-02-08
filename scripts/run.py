import queue, threading
from video_cl import VideoCliente, Mensaje

app = VideoCliente()

# Thread para el reproductor
t_player = threading.Thread(
    target = app.hilo_reproduccion
)

q_principal = app.q_back
q_player = app.q_player

q_principal.put(
    Mensaje(
        'PRINCIPAL',
        ''
    )
)

t_player.start()

app.SINCRO.sincronizar()

q_principal.put(
    Mensaje(
        'PRINCIPAL',
        'Iniciar'
    )
)

# Bucle principal: recibir mensajes de la app y coordinar sincro y reproducción
while True:
    msj = q_principal.get()

    if msj.origen == 'PRINCIPAL':
        # Enviar mensaje para detener e iniciar reproducción
        if msj.cmd == 'Detener'
            q_player.put(
                Mensaje(
                    'PRINCIPAL',
                    'stop_noidle'
                )
            )

        if msj.cmd == 'Iniciar':
            q_player.put(
                Mensaje(
                    'PRINCIPAL',
                    'play'
                )
            )

        if msj.cmd == 'Sincronizar':
            app.SINCRO.sincronizar()

            q_player.put(
                Mensaje(
                    'PRINCIPAL',
                    'list',
                    app.SINCRO.getListaLocal()
                )
            )
    
    if msj.origen == 'PLAYER':
        if msj.cmd == "Reproduciendo":
            app.SINCRO.registrarBeep(app.SINCRO.lista.id, msj.data['orden'])

            verificar = app.SINCRO.verificarContenido()
            if app.SINCRO.necesitoSincronizar() or verificar['eliminar'].__len__() > 0 or verificar['descargar'].__len__() > 0:
                q_principal.put(
                    Mensaje(
                        'PRINCIPAL',
                        'Detener'
                    )
                )
                q_principal.put(
                    Mensaje(
                        'PRINCIPAL',
                        'Sincronizar'
                    )
                )
                q_principal.put(
                    Mensaje(
                        'PRINCIPAL',
                        'Iniciar'
                    )
                )
        
        if msj.cmd == "Detenido":
            app.SINCRO.registrarBeep(app.SINCRO.lista.id, 0)

exit(0)
