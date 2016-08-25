export default function(img) {
    let canvas,
        ctx,
        i,
        pixels,
        value = '';

    canvas = document.createElement('canvas');
    canvas.width = img.width;
    canvas.height = img.height;

    ctx = canvas.getContext('2d');
    ctx.drawImage(img, 0, 0);

    pixels = ctx.getImageData(0, 0, canvas.width, canvas.height).data;

    for (i = 0; i < pixels.length; i += 4) {
        if (pixels[i] !== 0) {
            value += String.fromCharCode(pixels[i]);
        }

        if (pixels[i + 1] !== 0) {
            value += String.fromCharCode(pixels[i + 1]);
        }

        if (pixels[i + 2] !== 0) {
            value += String.fromCharCode(pixels[i + 2]);
        }
    }

    return value;
}