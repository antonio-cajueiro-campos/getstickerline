function tip(val) {
    switch (val) {
        case 1:
            swal("Verifique se o link do sticker existe, ex: https://store.line.me/stickershop/product/12856323");
            break;
        case 2:
            swal("Verifique se o link do sticker inserido é animado, ex: https://store.line.me/stickershop/product/9024195");
            break;
    }
}
function loadanimation() {
    document.getElementById("load").setAttribute("class", 'loadanim');    
    document.getElementById("msg").removeAttribute("class");
}