import queue, threading
from video_cl import VideoCliente, Mensaje

app = VideoCliente()

app.SINCRO.necesitoSincronizar()
exit(0)

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

# TODO: verificar que esté corriendo la reproducción
while True:
    msj = q_principal.get()

    if msj.origen == 'PRINCIPAL':
        if msj.cmd == 'Reiniciar' or msj.cmd == 'Iniciar':
            pass

    if msj.origen == 'SINCRO':
        if msj.cmd == "Finalizada":
            q_principal.put(
                msj(
                    'PRINCIPAL',
                    'Reiniciar'
                )
            )
    
    if msj.origen == 'PLAYER':
        if msj.cmd == "Reproduciendo":
            print("\t" + str(msj.data['orden']) + " -\t" + str(msj.data['archivo']))
        
        if msj.cmd == "Detenido":
            pass

exit(0)
