<?php

namespace App;

use App\BaseModel;
use Carbon\Carbon;

use App\ProcesoVideo;

class Video extends BaseModel
{
    const CARPETA_ORIGINALES = "files";
    const CARPETA_PROXY = "proxy";

    const DEFINICION_PROXY = 240;
    const DEFINICION_ORIGINAL = 1080;

    protected $table = "videos";
    protected $fillable = [
        'descripcion',
        'vigente_desde',
        'vigente_hasta',
    ];
    protected $appends = ['vigente'];

    public $timestamps = false;

    // *** ELOQUENT ***
    public function listas() {
        return $this->belongsToMany('App\Lista', 'lista_videos', 'id_video', 'id_lista');
    }

    public function proceso() {
        return $this->hasOne('App\ProcesoVideo', 'id_video', 'id');
    }

    // *** Atributos calculados ***

    /**
     * Devolver verdadero si el video fue procesado y convertido.
     *
     * El criterio es que tengo el path fuente hasta que lo convierto y empiezo a usar
     * los paths de las constantes de clase.
     */
    public function getDisponibleAttribute() {
        // Si el nombre de archivo de la BD es un path absoluto, todavía no se movió / procesó
        return !empty($this->hash);
    }

    /**
     * Devolver verdadero si el video está vigente ahora mismo
     */
    public function getVigenteAttribute() {
        return (!$this->vigente_desde && !$this->vigente_hasta) ||
            ($this->vigente_desde <= Carbon::now() && !$this->vigente_hasta) ||
            ($this->vigente_desde <= Carbon::now() && Carbon::now() <= $this->vigente_hasta)
            ;
    }

    /**
     * Devolver verdadero si el video está en formato retrato, o falso si es apaisado normal.
     */
    public function getRetratoAttribute() {
        return ($this->alto > $this->ancho);
    }

    /**
     * Devolver la descripción del video o, si está vacía, el nombre del archivo.
     */
    public function getNombreAttribute() {
        return empty($this->descripcion) ? $this->nombre_archivo : $this->descripcion;
    }

    /**
     * Devolver un string en formato i:s de la duración del video
     */
    public function getDuracionStrAttribute() {
        return Carbon::createFromTimestampUTC($this->duracion)->format('i:s');
    }

    /**
     * Ruta absoluta al archivo HQ
     */
    public function getFileAttribute() {
        return public_path(self::CARPETA_ORIGINALES.'/'.$this->nombre_archivo);
    }

    /**
     * Ruta absoluta al archivo proxy
     */
    public function getProxyAttribute() {
        return public_path(self::CARPETA_PROXY.'/'.$this->nombre_archivo);
    }

    public function getFileUrlAttribute() {
        return url(self::CARPETA_ORIGINALES.'/'.$this->nombre_archivo);
    }

    public function getProxyUrlAttribute() {
        return url(self::CARPETA_PROXY.'/'.$this->nombre_archivo);
    }

    /**
     * Devolver verdadero si el video está dispuesto como retrato
     */
    public function getEsRetratoAttribute() {
        $dimensiones = explode('x', $this->dimensiones);
        return $dimensiones[0] < $dimensiones[1];
    }

    /**
     * Devolver el alto del cuadro de video
     */
    public function getAltoAttribute() {
        $dimensiones = explode('x', $this->dimensiones);
        return $dimensiones[1];
    }

    /**
     * Devolver el ancho del cuadro de video
     */
    public function getAnchoAttribute() {
        $dimensiones = explode('x', $this->dimensiones);
        return $dimensiones[0];
    }

    // *** Métodos de CLASE ***

    /**
     * Obtener un video por ID
     */
    public static function getById($id) {
        return self::find($id);
    }

    /**
     * Obtener un video por hash
     */
    public static function getByHash($hash) {
        $vid = self::whereHash($hash)->get();
        return $vid ? $vid->first() : null;
    }

    /**
     * Filtrar videos que están vigentes
     */
    public static function getVigentes() {
        $retval = [];
        foreach(Video::all() as $video) {
            if ($video->vigente) {
                $retval[] = $video;
            }
        }

        return $retval;
    }

    /**
     * Crear la instancia del video previa validación
     */
    public static function crearVideo($path, $descripcion = '') {
        // Si la duración del archivo es 0, es inválido
        $duracion = self::getAtributoArchivo('duration', $path);
        if ($duracion == 0) {
            return null;
        }

        // Obtener las dimensiones usando FFProbe
        $ancho = self::getAtributoArchivo('width', $path);
        $alto = self::getAtributoArchivo('height', $path);

        $video = new Video();
        $video->descripcion = $descripcion;
        $video->dimensiones = $ancho.'x'.$alto;
        $video->duracion = $duracion;

        // Incluso si ya existiera, volver a subir archivo con esta función
        $video->nombre_archivo = $path;
        $video->save();

        // Devolver este video
        return $video;
    }

    /**
     * Devolver el valor de un atributo usando FFProbe.
     */
    public static function getAtributoArchivo($atributo, $path) {
        $ffprobe = \FFMpeg\FFProbe::create();

        return $ffprobe->streams($path)
            ->videos()
            ->first()
            ->get($atributo);
    }

    /**
     * Calcular las dimensiones de salida manteniendo la relación de aspecto.
     * $input es un array con dos componentes, $menor_borde es la resolución del menor de los bordes (ej: 1080, 720, 480, etc)
     *
     * Devuelve un array de dos componentes.
     */
    public static function calcularRedimensionar($input, $menor_borde) {
        // Si son iguales, devuelvo el mismo array de entrada
        if ($input[0] == $input[1]) {
            return $input;
        }

        // Detectar el menor de los bordes del array de entrada
        $pos_menor = 1;
        if ($input[0] < $input[1]) {
            $pos_menor = 0;
        }

        // Calcular la proporción y las nuevas dimensiones
        $proporcion = $menor_borde / $input[$pos_menor];
        $output = [
            (int) round($input[0] * $proporcion),
            (int) round($input[1] * $proporcion)
        ];

        // HACER QUE LAS DIMENSIONES DE SALIDA SEAN MÚLTIPLOS DE 4 PORQUE SI NO LA CODIFICACIÓN FALLA
        if ($output[0] % 4 != 4) {
            $output[0] = $output[0] + ($output[0] % 4);
        }

        if ($output[1] % 4 != 4) {
            $output[1] = $output[1] + ($output[1] % 4);
        }

        return $output;
    }

    // *** Métodos de INSTANCIA ***

    /**
     * Eliminar el video y los archivos asociados.
     */
    public function eliminarVideo() {
        $ruta_original = $this->file;
        $ruta_proxy = $this->proxy;

        $this->delete();

        unlink($ruta_original);
        unlink($ruta_proxy);
    }

    public static function nombreDisponible(&$nombre) {
        if (self::where('nombre_archivo', "$nombre")->count() > 0) {
            $nuevo_nombre = '-'.$nombre;
            $nombre = self::nombreDisponible($nuevo_nombre);
        }

        return $nombre;
    }

    /**
     * PROCESO DE TRANSCODIFICACIÓN CON FFMPEG
     *
     * Escupe dos MP4/AVC x264 + MP3Lame:
     *      # PROXY (streaming)
     *          V: 200 kbps
     *          A: 32 kbps
     *
     *      # ORIGINAL
     *          V: 6000 kbps
     *          A: 32 kbps
     */
    public function transcodificarOriginal() {
        // Si me da el atributo disponible es porque ya lo procesé
        if ($this->disponible) {
            return false;
        }

        // Setear path y aislar nombre de archivo del path
        $path = $this->nombre_archivo;
        $nombre_arch = basename($path);
        $nombre_export = self::nombreDisponible($nombre_arch);

        // Instanciar FFMpeg
        $ffmpeg = \FFMpeg\FFMpeg::create();

        // ------------------------------------------------------------

        // *** Procesamiento del _PROXY_ ***
        echo "-PROXY- BEGIN\n";
        $proceso = ProcesoVideo::generarProceso($this->id, 'PROXY');

        // Crear formato x264
        $format = new \FFMpeg\Format\Video\X264();
        // Callback para actualizar el proceso en BD
        $format->on('progress', function ($video, $format, $percentage) use ($proceso) {
            $proceso->setPorcentaje($percentage / 2);
            echo $proceso->porcentaje."\n";
        });

        // Atributos del formato para el archivo proxy
        $format->setKiloBitrate(200)            // Bajo bitrate de video
                ->setAudioChannels(1)           // Audio mono
                ->setAudioCodec('libmp3lame')   // Audio mp3
                ->setAudioKiloBitrate(32);      // Audio a 32 kbps... al pedo.

        // Abrir video de entrada y aplicar filtros para redimensionar y cambiar framerate
        $video = $ffmpeg->open($path);

        $calc = self::calcularRedimensionar(
            [$this->ancho, $this->alto], self::DEFINICION_PROXY
        );

        $ancho_target = $calc[0];
        $alto_target = $calc[1];

        // Instanciar las dimensiones obtenidas en un objeto Dimension para FFMpeg
        $dimension_destino = new \FFMpeg\Coordinate\Dimension($ancho_target, $alto_target);

        // Redimensionar a la dimensión obtenida y cambiar FPS a 12 con GOP = 5
        $video->filters()
                ->resize($dimension_destino)
                ->framerate(new \FFMpeg\Coordinate\FrameRate(12), 5);

        // EXPORTAR ->
        if (
            realpath(public_path(self::CARPETA_PROXY)) === false ||
            !is_dir(public_path(self::CARPETA_PROXY))
        ) {
            mkdir(public_path(self::CARPETA_PROXY));
        }
        $video->save($format, public_path(self::CARPETA_PROXY."/$nombre_export"));

        // Forzar el proceso a 50 una vez que haya terminado el save() del proxy
        $proceso->setPorcentaje(50);

        // ------------------------------------------------------------

        // *** Proceso del _ORIGINAL_ ***
        echo "-ORIGINAL- BEGIN\n";
        // Cambiar tipo de proceso a 'ORIGINAL'
        $proceso->tipo = 'ORIGINAL';
        $proceso->save();

        $format = new \FFMpeg\Format\Video\X264();
        $format->on('progress', function ($video, $format, $percentage) use ($proceso) {
            $proceso->setPorcentaje($percentage / 2 + 50);
            echo $proceso->porcentaje."\n";
        });

        $format->setKiloBitrate(6000)            // Bitrate de video medio (6mbps)
                ->setAudioChannels(1)           // Audio mono
                ->setAudioCodec('libmp3lame')   // Audio mp3
                ->setAudioKiloBitrate(32);      // Audio a 32 kbps... al pedo.

        // Abrir video de entrada y recomprimir
        $video = $ffmpeg->open($path);

        // EXPORTAR ->
        if (
            realpath(public_path(self::CARPETA_ORIGINALES)) === false ||
            !is_dir(public_path(self::CARPETA_ORIGINALES))
        ) {
            mkdir(public_path(self::CARPETA_ORIGINALES));
        }
        $video->save($format, public_path(self::CARPETA_ORIGINALES."/$nombre_export"));

        // Forzar el proceso a 100 una vez que haya terminado el save()
        $proceso->setPorcentaje(100);

        // ------------------------------------------------------------

        // Llegué hasta acá, cambio el nombre_archivo del video, le doy vigencia_desde y guardo
        $this->nombre_archivo = $nombre_export;
        $this->hash = sha1_file(public_path(self::CARPETA_ORIGINALES."/$nombre_export"));
        $this->vigente_desde = Carbon::now();
        $this->save();

        return $this;
    }
}
