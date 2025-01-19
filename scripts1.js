document.addEventListener("DOMContentLoaded", function () {
    const buttons = {
        showDolphinButton: document.querySelectorAll("#DolphinImage"),
        showGiantPandaButton: document.querySelectorAll("#GiantPandaImage"),
        showAsianElephantButton: document.querySelectorAll("#AsianElephantImage"),
        showWildDeerButton: document.querySelectorAll("#WildDeerImage"),
        showGiraffeButton: document.querySelectorAll("#GiraffeImage"),
        showZebraButton: document.querySelectorAll("#ZebraImage"),
        showHorseButton: document.querySelectorAll("#HorseImage"),
        showKangarooButton: document.querySelectorAll("#KangarooImage"),
        showLionButton: document.querySelectorAll("#LionImage"),
        showTigerButton: document.querySelectorAll("#TigerImage"),
        showBearButton: document.querySelectorAll("#BearImage"),
        showWolfButton: document.querySelectorAll("#WolfImage"),
        showCheetahButton: document.querySelectorAll("#CheetahImage"),
        showRhinocerosButton: document.querySelectorAll("#RhinocerosImage"),
        showHippopotamusButton: document.querySelectorAll("#HippopotamusImage"),
        showCapybaraButton: document.querySelectorAll("#CapybaraImage"),
        showSlothButton: document.querySelectorAll("#SlothImage"),
        showKoalaButton: document.querySelectorAll("#KoalaImage"),
        showSugarGlinderButton: document.querySelectorAll("#SugarGlinderImage"),
        showChimpanzeeButton: document.querySelectorAll("#ChimpanzeeImage"),
        showOrangutanButton: document.querySelectorAll("#OrangutanImage"),
        showGorillaButton: document.querySelectorAll("#GorillaImage"),
        showTurtleButton: document.querySelectorAll("#TurtleImage"),
        showPorcupineButton: document.querySelectorAll("#PorcupineImage"),
        showPoisonousFrogButton: document.querySelectorAll("#PoisonousFrogImage"),
        showIguanaButton: document.querySelectorAll("#IguanaImage"),
        showCrocodileButton: document.querySelectorAll("#CrocodileImage"),
        showPythonButton: document.querySelectorAll("#PythonImage"),
        showSnakeButton: document.querySelectorAll("#SnakeImage"),
        showFlamingoButton: document.querySelectorAll("#FlamingoImage"),
        showOstrichButton: document.querySelectorAll("#OstrichImage"),
        showPeacockButton: document.querySelectorAll("#PeacockImage"),
        showSwanButton: document.querySelectorAll("#SwanImage"),
        showBoldEagleButton: document.querySelectorAll("#BoldEagleImage"),
        showParrotButton: document.querySelectorAll("#ParrotImage"),
        showMacowButton: document.querySelectorAll("#MacowImage"),
        showToucanButton: document.querySelectorAll("#ToucanImage"),
        showOwlButton: document.querySelectorAll("#OwlImage"),
        showKingfisherButton: document.querySelectorAll("#KingfisherImage"),
        showTarantulaButton: document.querySelectorAll("#TarantulaImage")
    };

    const modal = document.getElementById("imageModal");
    const modalImage = document.getElementById("modalImage");
    const captionText = document.getElementById("caption");
    const closeBtn = document.querySelector(".close");

    function showImageInModal(images) {
        modal.style.display = "block";
        modalImage.src = images[0].src;
        captionText.innerHTML = images[0].alt;
    }

    closeBtn.onclick = function() {
        modal.style.display = "none";
    };

    Object.keys(buttons).forEach(buttonId => {
        const button = document.getElementById(buttonId);
        button.addEventListener("click", () => showImageInModal(buttons[buttonId]));
    });
});
