<div align="center" class="row" style="background: black;">
	<video style="{{ $video->es_retrato ? 'height' : 'width' }}: 90%;" autoplay loop>
        <source src="{{ $video->proxy_url }}" type="video/mp4">
    </video>
</div>
<div class="container-fluid" style="margin-top: 1em;" align="center">
    <a href="{{ $video->file_url }}" target="_blank"><i class="fa fa-download"></i>&nbsp;&nbsp;Descargar original</a><br>
    <small>(botón derecho en el enlace y <i>Guardar destino como...</i>)</small>
</div>
