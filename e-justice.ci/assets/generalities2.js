function showLoadingModal(text) {

    $("body").loadingModal({
        position: "auto",
        text: text,
        color: "#fa893f",
        opacity: "0.7",
        backgroundColor: "rgb(0,0,0)", //rgb(0,0,0)
        animation: "circle", //wave, wanderingCubes, chasingDots, threeBounce, circle, cubeGrid, fadingCircle, foldingCube
    });
    $("body").addClass('overflow-hidden');
    setTimeout(hideLoadingModal, 9000000);
}

function hideLoadingModal() {
    $("body").loadingModal("hide");
    $("body").loadingModal("destroy");
    $("body").removeClass('overflow-hidden');
}

const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
});

function IsValidDate(day) {
    var pattern = new RegExp(/^\d{4}-\d{2}-\d{2}$/);
    console.log(pattern.test(day));
    return pattern.test(day);
}


