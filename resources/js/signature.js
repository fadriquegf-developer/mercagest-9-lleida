import SignaturePad from "signature_pad";

var canvas = document.getElementById("signature-pad");

var signaturePad = new SignaturePad(canvas);

document
    .getElementById("clear-signature-pad")
    .addEventListener("click", function () {
        signaturePad.clear();
        document.getElementById("signature").value = "";
    });

document
    .getElementById("save-signature-pad")
    .addEventListener("click", function (event) {
        if (!signaturePad.isEmpty()) {
            var data = signaturePad.toDataURL("image/png");
            document.getElementById("signature").value = data;
        }
    });
