<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Video;

class ProcesarVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $id_video;
    private $video;

    /**
     * Create a new job instance.
     * Recibe un Video para crear y ejecutar el proceso
     *
     * @return void
     */
    public function __construct($id_video)
    {
        $this->id_video = $id_video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->video = Video::find($this->id_video);
            if (!$this->video) {
                echo "id_video invalido: $this->id_video\n";
                return true;
            }

            // Path original
            $source = $this->video->nombre_archivo;

            if (!$this->video->transcodificarOriginal()) {
                throw new Exception("El registro no se guardo.");
            }

            // Borrar el archivo fuente
            unlink($source);

            return true;
        }
        catch (\Exception $e) {
            echo "Video ID: ".$this->id_video." - fallo: ".$e->getMessage()."\nSe borra.\n";
            try {
                $this->video->eliminarVideo();
            }
            catch(\Exception $ex) {
                // *shrug*
            }
            return true;
        }
    }
}
