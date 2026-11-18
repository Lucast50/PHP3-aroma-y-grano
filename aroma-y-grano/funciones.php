<?php
    if(session_status() === PHP_SESSION_NONE){
        session_start();

    }
    
    function limpiar_dato($dato){
        return htmlspecialchars(trim($dato), ENT_QUOTES, 'UTF-8');
    }

    function procesar_imagen_gd($archivo, $carpeta_destino) {
    if (!isset($archivo) || $archivo['error'] !== UPLOAD_ERR_OK)
    {
    return false;
    }

    $nombre_tmp = $archivo['tmp_name'];
    $info_imagen = getimagesize($nombre_tmp);

    if ($info_imagen === false) {
        return false; // No es una imagen válida
    }

    $ancho_orig = $info_imagen[0];
    $alto_orig = $info_imagen[1];
    $tipo = $info_imagen[2];

    switch ($tipo) {
        case IMAGETYPE_JPEG:
            $imagen_orig = imagecreatefromjpeg($nombre_tmp);
            break;
        case IMAGETYPE_PNG:
            $imagen_orig = imagecreatefrompng($nombre_tmp);
            break;
        case IMAGETYPE_WEBP:
            $imagen_orig = imagecreatefromwebp($nombre_tmp);
            break;
        default:
            return false;
        }
        
    $ancho_nuevo = 800;
    $alto_nuevo = floor($alto_orig * ($ancho_nuevo /
    $ancho_orig));

    $imagen_nueva = imagecreatetruecolor($ancho_nuevo, $alto_nuevo);
    if ($tipo == IMAGETYPE_PNG || $tipo == IMAGETYPE_WEBP) {
        imagecolortransparent($imagen_nueva, imagecolorallocatealpha($imagen_nueva, 0, 0, 0, 127));
        imagealphablending($imagen_nueva, false);
        imagesavealpha($imagen_nueva, true);
        
        }
    
    
    imagecopyresampled(
        $imagen_nueva, // 1. Lienzo de destino
        $imagen_orig, // 2. Imagen original
        0, 0, // 3 y 4. Coordenada X e Y en el destino
        0, 0, // 5 y 6. Coordenada X e Y en el origen
        $ancho_nuevo, // 7. Ancho final en el destino
        $alto_nuevo, // 8. Alto final en el destino
        $ancho_orig, // 9. Ancho original de la imagen
        $alto_orig // 10. Alto original de la imagen
    );

    $nombre_archivo = uniqid('cafe_') . '.jpg';
    $ruta_completa = $carpeta_destino . $nombre_archivo;

    imagejpeg($imagen_nueva, $ruta_completa, 85);

    imagedestroy($imagen_orig);
    imagedestroy($imagen_nueva);

    return $nombre_archivo;
    }
?>