$(document).ready(function () {
    "use strict";
    let baseURL = $("#hidden_base_url").val();
    $(document).on("change", ".image_preview", function () {
        let files = $(this)[0].files;
        let container = $(".image-preview-container");
        container.empty(); // Clear the container before appending new files

        $.each(files, function (index, file) {
            let reader = new FileReader();
            reader.onload = function (e) {
                let fileExtension = file.name.split(".").pop();
                if (
                    fileExtension === "png" ||
                    fileExtension === "jpg" ||
                    fileExtension === "jpeg" ||
                    fileExtension === "gif"
                ) {
                    container.append(
                        `<img src="${e.target.result}" alt="Image Preview ${index + 1}" class="img-thumbnail mx-2" width="100px">`
                    );
                } else if (fileExtension === "pdf") {
                    container.append(
                        `<a class="text-decoration-none" href="${e.target.result}" target="_blank">
                            <img src="${baseURL}assets/images/pdf.png" alt="PDF Preview" class="img-thumbnail" width="100px">
                        </a>`
                    );
                } else if (fileExtension === "doc" || fileExtension === "docx") {
                    container.append(
                        `<a class="text-decoration-none" href="${e.target.result}" target="_blank">
                            <img src="${baseURL}assets/images/word.png" alt="Word Preview" class="img-thumbnail" width="100px">
                        </a>`
                    );
                } else {
                    container.append(`<p>Unsupported file format.</p>`);
                }
            };
            reader.readAsDataURL(file);
        });
    });
});
