let selectedTambahFiles = [];

window.previewMultipleImages = function (event) {
    let files = [];

    if (event && event.target && event.target.files) {
        files = Array.from(event.target.files);
    } else {
        console.error("Parameter tidak dikenali:", event);
        return;
    }

    const validTypes = [
        "image/jpeg",
        "image/png",
        "image/jpg",
        "image/gif",
        "image/webp",
    ];

    const maxSize = 2 * 1024 * 1024; // 2MB

    const imageWarning = document.getElementById("imageSizeWarning");
    const imageWarningText = document.getElementById("imageSizeWarningText");

    let hasError = false;

    files.forEach((file) => {
        const isValidType = validTypes.includes(file.type);

        if (!isValidType) {
            hasError = true;

            if (imageWarningText) {
                imageWarningText.textContent = `File "${file.name}" bukan format gambar yang didukung`;
            }

            if (imageWarning) {
                imageWarning.style.display = "flex";
            }

            return;
        }

        if (file.size > maxSize) {
            hasError = true;

            if (imageWarningText) {
                imageWarningText.textContent = `Ukuran file "${file.name}" melebihi batas 2MB`;
            }

            if (imageWarning) {
                imageWarning.style.display = "flex";
            }

            return;
        }

        selectedTambahFiles.push(file);
    });

    if (!hasError && imageWarning) {
        imageWarning.style.display = "none";
    }

    renderPreviewImages();

    const fileInput = document.getElementById("imageInput");
    if (fileInput) {
        const dt = new DataTransfer();

        selectedTambahFiles.forEach((file) => {
            dt.items.add(file);
        });

        fileInput.files = dt.files;
    }
};

window.removeImageTambah = function (event, index) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }

    selectedTambahFiles.splice(index, 1);
    renderPreviewImages();
};

function renderPreviewImages() {
    const preview = document.getElementById("imagePreview");
    const uploadLabel = document.getElementById("imageUploadLabel");
    const fileInput = document.getElementById("imageInput");

    if (!preview || !uploadLabel || !fileInput) return;

    const dt = new DataTransfer();

    selectedTambahFiles.forEach((file) => {
        dt.items.add(file);
    });

    fileInput.files = dt.files;

    preview.innerHTML = "";

    if (selectedTambahFiles.length === 0) {
        preview.style.display = "none";
        uploadLabel.style.display = "block";
        return;
    }

    uploadLabel.style.display = "none";
    preview.style.display = "flex";

    selectedTambahFiles.forEach((file, index) => {
        const reader = new FileReader();

        const imageContainer = document.createElement("div");
        imageContainer.style.cssText = `
            width: 100px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            background: white;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            position: relative;
            flex-shrink: 0;
        `;

        imageContainer.innerHTML = `
            <div style="width: 100%; height: 80px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; position: relative;">
                <button type="button"
                    onclick="window.removeImageTambah(event, ${index})"
                    style="position: absolute; top: 4px; right: 4px; background: rgba(239, 68, 68, 0.9); color: white; border: none; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 10px; z-index: 10;"
                    title="Hapus Gambar">
                    <i class="ti-close"></i>
                </button>
                <i class="ti-reload" style="font-size: 20px; color: #9ca3af; animation: spin 1s linear infinite;"></i>
            </div>
            <div style="padding: 6px; text-align: center; background: #f9fafb; border-top: 1px solid #f0f0f0;">
                <small style="font-size: 10px; color: #6b7280; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${file.name}">
                    ${file.name.substring(0, 12)}${file.name.length > 12 ? "..." : ""}
                </small>
            </div>
        `;

        reader.onload = function (e) {
            const imgDiv = imageContainer.querySelector("div:first-child");
            if (imgDiv) {
                const reloadIcon = imgDiv.querySelector(".ti-reload");
                if (reloadIcon) reloadIcon.remove();

                const img = document.createElement("img");
                img.src = e.target.result;
                img.style.cssText =
                    "width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; z-index: 1;";
                imgDiv.appendChild(img);
            }
        };

        reader.readAsDataURL(file);
        preview.appendChild(imageContainer);
    });

    const addMoreContainer = document.createElement("label");
    addMoreContainer.setAttribute("for", "imageInput");
    addMoreContainer.style.cssText = `
        width: 100px;
        height: 109px;
        border: 2px dashed #00a669;
        border-radius: 8px;
        background: #ecfdf5;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        flex-shrink: 0;
        color: #00a669;
        margin: 0;
    `;

    addMoreContainer.innerHTML = `
        <i class="ti-plus" style="font-size: 24px; margin-bottom: 4px;"></i>
        <span style="font-size: 11px; font-weight: 600;">Tambah</span>
    `;

    preview.appendChild(addMoreContainer);
}

window.selectedFilesEditDataTransfers = {};
const selectedFilesEditDataTransfers = window.selectedFilesEditDataTransfers;

/**
 * Preview multiple images for EDIT modal
 */
function previewMultipleImagesEdit(event, id) {
    let files = [];

    if (event && event.target && event.target.files) {
        files = Array.from(event.target.files);
    } else {
        console.error("Parameter tidak dikenali");
        return;
    }

    const validTypes = [
        "image/jpeg",
        "image/png",
        "image/jpg",
        "image/gif",
        "image/webp",
    ];

    const maxSize = 2 * 1024 * 1024;

    const imageWarning = document.getElementById("imageSizeWarningEdit" + id);
    const imageWarningText = document.getElementById("imageSizeWarningTextEdit" + id);
    const fileInput = document.getElementById("imageInput" + id);

    if (!selectedFilesEditDataTransfers[id]) {
        selectedFilesEditDataTransfers[id] = new DataTransfer();
    }

    let hasError = false;
    const validFiles = [];

    files.forEach((file) => {
        if (!validTypes.includes(file.type)) {
            hasError = true;

            if (imageWarningText) {
                imageWarningText.textContent = `File "${file.name}" bukan format gambar yang didukung`;
            }

            if (imageWarning) {
                imageWarning.style.display = "flex";
            }

            return;
        }

        if (file.size > maxSize) {
            hasError = true;

            if (imageWarningText) {
                imageWarningText.textContent = `Ukuran file "${file.name}" melebihi batas 2MB`;
            }

            if (imageWarning) {
                imageWarning.style.display = "flex";
            }

            return;
        }

        validFiles.push(file);
    });

    if (hasError && validFiles.length === 0) {
        if (fileInput) {
            fileInput.files = selectedFilesEditDataTransfers[id].files;
        }

        return;
    }

    if (!hasError && imageWarning) {
        imageWarning.style.display = "none";
    }

    validFiles.forEach((file) => {
        selectedFilesEditDataTransfers[id].items.add(file);
    });

    renderPreviewImagesEdit(id);

    if (fileInput) {
        fileInput.files = selectedFilesEditDataTransfers[id].files;
    }

    console.log("File edit tersimpan di input:", fileInput.files.length);
}

window.removeImageEdit = function (id, index) {
    if (!selectedFilesEditDataTransfers[id]) return;

    const newDt = new DataTransfer();
    const files = selectedFilesEditDataTransfers[id].files;

    for (let i = 0; i < files.length; i++) {
        if (i !== index) {
            newDt.items.add(files[i]);
        }
    }

    selectedFilesEditDataTransfers[id] = newDt;
    renderPreviewImagesEdit(id);
};

function renderPreviewImagesEdit(id) {
    const preview = document.getElementById("imagePreviewEdit" + id);
    const uploadLabel = document.getElementById("imageUploadLabelEdit" + id);
    const fileInput = document.getElementById("imageInput" + id);

    if (!preview || !uploadLabel || !fileInput) return;

    if (!selectedFilesEditDataTransfers[id]) {
        selectedFilesEditDataTransfers[id] = new DataTransfer();
    }

    fileInput.files = selectedFilesEditDataTransfers[id].files;
    preview.innerHTML = "";
    const files = selectedFilesEditDataTransfers[id].files;

    if (files.length === 0) {
        fileInput.value = "";
        preview.style.display = "none";
        uploadLabel.style.display =
            uploadLabel.getAttribute("data-original-display") || "";
        return;
    }

    if (!uploadLabel.hasAttribute("data-original-display")) {
        uploadLabel.setAttribute(
            "data-original-display",
            uploadLabel.style.display,
        );
    }
    uploadLabel.style.display = "none";
    preview.style.display = "flex";

    Array.from(files).forEach((file, index) => {
        const reader = new FileReader();
        const imageDiv = document.createElement("div");
        imageDiv.style.cssText = `
            width: 100px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            background: white;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            position: relative;
            flex-shrink: 0;
        `;

        imageDiv.innerHTML = `
            <div style="width: 100%; height: 80px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; position: relative;">
                <button type="button" onclick="window.removeImageEdit(${id}, ${index})" style="position: absolute; top: 4px; right: 4px; background: rgba(239, 68, 68, 0.9); color: white; border: none; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 10px; z-index: 10; transition: background 0.2s;" onmouseover="this.style.background='#dc2626'" onmouseout="this.style.background='rgba(239, 68, 68, 0.9)'" title="Hapus Gambar">
                    <i class="ti-close"></i>
                </button>
                <i class="ti-reload" style="font-size: 20px; color: #9ca3af; animation: spin 1s linear infinite;"></i>
            </div>
            <div style="padding: 6px; text-align: center; background: #f9fafb; border-top: 1px solid #f0f0f0;">
                <small style="font-size: 10px; color: #6b7280; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${file.name}">
                    ${file.name.substring(0, 12)}${file.name.length > 12 ? "..." : ""}
                </small>
            </div>
        `;

        reader.onload = function (e) {
            const imgContainer = imageDiv.querySelector("div:first-child");
            if (imgContainer) {
                const reloadIcon = imgContainer.querySelector(".ti-reload");
                if (reloadIcon) reloadIcon.remove();

                const img = document.createElement("img");
                img.src = e.target.result;
                img.style.cssText =
                    "width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; z-index: 1;";
                imgContainer.appendChild(img);
            }
        };

        reader.readAsDataURL(file);
        preview.appendChild(imageDiv);
    });

    const addMoreContainer = document.createElement("label");
    addMoreContainer.setAttribute("for", "imageInput" + id);
    addMoreContainer.style.cssText = `
        width: 100px;
        height: 109px;
        border: 2px dashed #3b82f6;
        border-radius: 8px;
        background: #eff6ff;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        flex-shrink: 0;
        transition: all 0.2s;
        color: #3b82f6;
        margin: 0;
    `;
    addMoreContainer.onmouseover = function () {
        this.style.background = "#dbeafe";
    };
    addMoreContainer.onmouseout = function () {
        this.style.background = "#eff6ff";
    };
    addMoreContainer.innerHTML = `
        <i class="ti-plus" style="font-size: 24px; margin-bottom: 4px;"></i>
        <span style="font-size: 11px; font-weight: 600;">Tambah</span>
    `;
    preview.appendChild(addMoreContainer);
}


window.setMainImage = async function setMainImage(imageId, kamarId) {
    try {
        const csrfToken =
            document.querySelector('meta[name="csrf-token"]')?.content || "";

        const response = await fetch(`/kamar/image/${imageId}/main`, {
            method: "PUT",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "Accept": "application/json",
            },
        });

        console.log("SET MAIN status:", response.status);

        const text = await response.text();
        console.log("SET MAIN response:", text);

        let data;
        try {
            data = JSON.parse(text);
        } catch (e) {
            showNotification("Response bukan JSON. Cek Console.", "error");
            return;
        }

        if (response.ok && data.success) {
            showNotification(data.message || "Gambar utama berhasil diubah", "success");

            setTimeout(() => {
                location.reload();
            }, 700);
        } else {
            showNotification(data.message || "Gagal mengubah gambar utama", "error");
        }
    } catch (error) {
        console.error("Error set main image:", error);
        showNotification("Terjadi kesalahan saat mengubah gambar utama", "error");
    }
};

// ========== NOTIFICATION SYSTEM ==========

/**
 * Show notification toast
 */
window.showNotification = function (message, type = "success") {
    const existingNotifications = document.querySelectorAll(
        ".custom-notification",
    );
    existingNotifications.forEach((notif) => notif.remove());

    const notification = document.createElement("div");
    notification.className = `custom-alert ${type} custom-notification`;
    notification.innerHTML = `
        <div class="custom-alert-icon">
            <i class="${type === "success" ? "ti-check" : "ti-alert"}"></i>
        </div>
        <div class="custom-alert-content">${message}</div>
        <button type="button" class="custom-alert-close" onclick="this.parentElement.remove()">
            <i class="ti-close"></i>
        </button>
    `;

    document.body.appendChild(notification);
    setTimeout(() => {
        if (notification && notification.parentNode) {
            notification.remove();
        }
    }, 3000);
};

// ========== FORM PILL SELECTORS ==========

/**
 * Select tipe pill in create modal
 */
// file: kamar.js atau di dalam tag <script>

// WAJIB menggunakan window. agar bisa dipanggil oleh onchange="..." di HTML
window.selectTipe = function (radio) {
    document.querySelectorAll("#tipeGrid .pill-label").forEach((el) => {
        el.classList.remove("pill-active-tipe");
    });

    const lbl = radio.nextElementSibling;
    if (lbl) {
        lbl.classList.add("pill-active-tipe");
    }

    const warning = document.getElementById("tipeWarning");
    if (warning) warning.style.display = "none";
};

window.selectStatus = function (radio) {
    document.querySelectorAll("#statusGrid .pill-label").forEach((el) => {
        el.classList.remove("pill-active-status");
    });

    const lbl = radio.nextElementSibling;
    if (lbl) {
        lbl.classList.add("pill-active-status");
    }

    const warning = document.getElementById("statusWarning");
    if (warning) warning.style.display = "none";
};

window.updateFasilitasCount = function() {
    const checkboxes = document.querySelectorAll('.fasilitas-checkbox:checked');
    const countSpan = document.getElementById('fasilitasSelectedCount');
    if (countSpan) {
        if (checkboxes.length === 0) {
            countSpan.textContent = 'Pilih Fasilitas';
            countSpan.style.color = '#9ca3af';
        } else {
            countSpan.textContent = checkboxes.length + ' Fasilitas dipilih';
            countSpan.style.color = '#111827';
        }
    }
};

/**
 * Update pill in edit modal
 */
window.updateEditPill = function (radio, type, id) {
    const parent = radio.closest(".modal-body");

    if (type === "tipe") {
        document.querySelectorAll(`.pill-label-tipe-${id}`).forEach((el) => {
            el.classList.remove("pill-active-edit");
            el.style.background = "#f9fafb";
            el.style.color = "#6b7280";
            el.style.borderColor = "#e5e7eb";
            el.style.fontWeight = "500";
        });
        const selectedLabel = radio.nextElementSibling;
        if (selectedLabel) {
            selectedLabel.classList.add("pill-active-edit");
            selectedLabel.style.background = "#ecfdf5";
            selectedLabel.style.color = "#00a669";
            selectedLabel.style.borderColor = "#00a669";
            selectedLabel.style.fontWeight = "600";
        }
    } else if (type === "status") {
        document.querySelectorAll(`.pill-label-status-${id}`).forEach((el) => {
            el.classList.remove("pill-active-edit");
            el.style.background = "#f9fafb";
            el.style.color = "#6b7280";
            el.style.borderColor = "#e5e7eb";
            el.style.fontWeight = "500";
        });
        const selectedLabel = radio.nextElementSibling;
        if (selectedLabel) {
            selectedLabel.classList.add("pill-active-edit");
            selectedLabel.style.background = "#ecfdf5";
            selectedLabel.style.color = "#00a669";
            selectedLabel.style.borderColor = "#00a669";
            selectedLabel.style.fontWeight = "600";
        }
    }
};

// ========== MODAL RESET ==========

/**
 * Reset create modal on close
 */
function initModalReset() {
    const tambahModal = document.getElementById("tambahKamarModal");

    if (tambahModal) {
        tambahModal.addEventListener("hidden.bs.modal", function () {
            // 1. Reset Form HTML bawaan
            const form = this.querySelector("form");
            if (form) form.reset();

            // 2. Reset Visual Grid (Border Merah & Background)
            const grids = ["tipeGrid", "statusGrid"];
            grids.forEach((id) => {
                const el = document.getElementById(id);
                if (el) {
                    el.style.background = "transparent";
                    el.style.boxShadow = "none";
                    el.style.padding = "0";
                }
            });

            // 3. Sembunyikan Pesan Error JS (Teks Merah)
            const warnings = ["duplicateWarning", "tipeWarning", "statusWarning"];
            warnings.forEach((id) => {
                const el = document.getElementById(id);
                if (el) el.style.display = "none";
            });

            // 4. Reset Gaya Visual Pill (Kembali ke Abu-abu)
            document
                .querySelectorAll("#tambahKamarModal .pill-label")
                .forEach((el) => {
                    el.classList.remove("pill-active-tipe", "pill-active-status");
                });

            // 5. Uncheck semua Radio Button
            document
                .querySelectorAll('#tambahKamarModal input[type="radio"]')
                .forEach((r) => {
                    r.checked = false;
                });

            // 6. Reset Fasilitas (Khusus jika menggunakan Select2 atau dropdown multiple)
            // Ganti '#fasilitasSelect' dengan ID elemen select Anda
            const fasilitasSelect = $("#fasilitasSelect");
            if (fasilitasSelect.length > 0) {
                fasilitasSelect.val(null).trigger("change");
            }

            // 7. Reset Preview Gambar & DataTransfer
            selectedTambahFiles = [];
            const preview = document.getElementById("imagePreview");
            const uploadLabel = document.getElementById("imageUploadLabel");
            const fileInput = document.getElementById("imageInput");

            if (preview) {
                preview.innerHTML = "";
                preview.style.display = "none";
            }
            if (uploadLabel) {
                uploadLabel.style.display = "block";
            }
            if (fileInput) {
                fileInput.value = "";
            }

            console.log("Modal Tambah Kamar berhasil dibersihkan.");
        });
    }

    // --- RESET UNTUK MODAL EDIT (Jika Anda memiliki banyak modal edit) ---
    document.querySelectorAll('.modal[id^="editModal"]').forEach((modal) => {
        modal.addEventListener("hidden.bs.modal", function () {
            const kamarId = this.id.replace("editModal", "");

            const imageWarningEdit = document.getElementById(
                "imageSizeWarningEdit" + kamarId
            );

            if (imageWarningEdit) {
                imageWarningEdit.style.display = "none";
            }

            // Reset newly added images preview untuk edit modal
            if (
                selectedFilesEditDataTransfers &&
                selectedFilesEditDataTransfers[kamarId]
            ) {
                selectedFilesEditDataTransfers[kamarId] = new DataTransfer();
                renderPreviewImagesEdit(kamarId);
            }

            // Reset border error pada modal edit
            const editTipeGrid = document.getElementById(
                "tipeGridEdit" + kamarId,
            );
            const editStatusGrid = document.getElementById(
                "statusGridEdit" + kamarId,
            );
            if (editTipeGrid) editTipeGrid.style.border = "none";
            if (editStatusGrid) editStatusGrid.style.border = "none";
        });
    });
}

// ========== FORM SUBMIT HANDLERS ==========

/**
 * Initialize form submit handlers
 */
function initFormHandlers() {
    const kamarForms = [
        ...document.querySelectorAll('form[id="formTambahKamar"]'),
        ...document.querySelectorAll('form[id^="formEditKamar"]'),
    ];

    kamarForms.forEach((form) => {
        form.addEventListener("submit", function (e) {
            let isValid = true;
            
            // Pastikan file tambah kamar masuk ke input file sebelum submit
            if (this.id === "formTambahKamar") {
                const fileInput = document.getElementById("imageInput");

                if (fileInput && selectedTambahFiles.length > 0) {
                    const dt = new DataTransfer();

                    selectedTambahFiles.forEach((file) => {
                        dt.items.add(file);
                    });

                    fileInput.files = dt.files;

                    console.log("File tambah kamar yang dikirim:", fileInput.files.length);
                }
            }

            // Pastikan file edit yang sudah dipilih benar-benar masuk ke input file
            if (this.id.startsWith("formEditKamar")) {
                const kamarId = this.id.replace("formEditKamar", "");
                const fileInput = document.getElementById("imageInput" + kamarId);

                if (
                    fileInput &&
                    selectedFilesEditDataTransfers[kamarId] &&
                    selectedFilesEditDataTransfers[kamarId].files.length > 0
                ) {
                    fileInput.files = selectedFilesEditDataTransfers[kamarId].files;
                    console.log(
                        "File yang dikirim untuk kamar",
                        kamarId,
                        ":",
                        fileInput.files.length
                    );
                }
            }

            const tipeTerpilih = this.querySelector('input[name="tipe_kamar"]:checked');
            const statusTerpilih = this.querySelector('input[name="status_kamar"]:checked');

            const tipeWarning = document.getElementById("tipeWarning");
            const statusWarning = document.getElementById("statusWarning");

            if (!tipeTerpilih) {
                if (tipeWarning) tipeWarning.style.display = "flex";
                isValid = false;
            } else {
                if (tipeWarning) tipeWarning.style.display = "none";
            }

            if (!statusTerpilih) {
                if (statusWarning) statusWarning.style.display = "flex";
                isValid = false;
            } else {
                if (statusWarning) statusWarning.style.display = "none";
            }

            const inputNomor = this.querySelector('input[name="nomor_kamar"]');
            const duplicateWarning = document.getElementById("duplicateWarning");

            if (inputNomor && inputNomor.classList.contains("is-duplicate")) {
                if (duplicateWarning) duplicateWarning.style.display = "flex";
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                return false;
            }
        });
    });
}

// ========== AUTO HIDE ALERTS ==========

/**
 * Auto hide alerts after 3 seconds
 */
function initAutoHideAlerts() {
    setTimeout(function () {
        const alerts = document.querySelectorAll(".custom-alert");
        alerts.forEach((alert) => {
            alert.style.display = "none";
        });
    }, 3000);
}

// ========== EVENT LISTENERS ==========

/**
 * Initialize all event listeners
 */
function initEventListeners() {
    // File input untuk EDIT modals
    document.querySelectorAll('input[id^="imageInput"]').forEach((input) => {
        if (input.id !== "imageInput") {
            const kamarId = input.id.replace("imageInput", "");
            input.addEventListener("change", function (e) {
                console.log("Upload edit terdeteksi untuk kamar:", kamarId);
                console.log("Jumlah file dipilih:", e.target.files.length);

                if (e.target.files && e.target.files.length > 0) {
                    previewMultipleImagesEdit(e, kamarId);
                } else {
                    if (selectedFilesEditDataTransfers[kamarId]) {
                        this.files = selectedFilesEditDataTransfers[kamarId].files;
                    }
                }
            });
        }
    });

    document.querySelectorAll(".btn-delete-image").forEach((btn) => {
        btn.addEventListener("click", function () {
            const imageId = this.dataset.id;
            const kamarId = this.dataset.kamar;
            deleteImage(imageId, kamarId);
        });
    });

}

/**
 * Initialize validation for nomor kamar
 */
function initKamarValidation() {
    const inputNomor = document.getElementById("inputNomorKamar");
    const warning = document.getElementById("duplicateWarning");

    const existingNumbers = (window.existingKamarNumbers || []).map(n =>
        String(n).trim().toLowerCase()
    );

    if (inputNomor) {
        inputNomor.addEventListener("input", function () {
            const val = String(this.value).trim().toLowerCase();

            if (val && existingNumbers.includes(val)) {
                this.classList.add("is-duplicate");
                if (warning) warning.style.display = "flex";
            } else {
                this.classList.remove("is-duplicate");
                if (warning) warning.style.display = "none";
            }
        });
    }

    document.querySelectorAll('form[id^="formEditKamar"]').forEach((form) => {
        const inputNomorEdit = form.querySelector('input[name="nomor_kamar"]');
        const kamarId = form.id.replace("formEditKamar", "");
        const warningEdit = document.getElementById("duplicateWarningEdit" + kamarId);

        if (inputNomorEdit && !inputNomorEdit.hasAttribute("data-original")) {
            inputNomorEdit.setAttribute(
                "data-original",
                String(inputNomorEdit.value).trim().toLowerCase()
            );
        }

        if (inputNomorEdit) {
            inputNomorEdit.addEventListener("input", function () {
                const val = String(this.value).trim().toLowerCase();
                const originalVal = this.getAttribute("data-original");

                const isTaken =
                    val &&
                    existingNumbers.includes(val) &&
                    val !== originalVal;

                const submitBtn = form.querySelector('button[type="submit"]');

                if (isTaken) {
                    if (warningEdit) warningEdit.style.display = "flex";

                    this.classList.add("is-duplicate");
                    this.style.borderColor = "#ef4444";
                    this.style.boxShadow = "0 0 0 3px rgba(239,68,68,0.1)";

                    if (submitBtn) submitBtn.disabled = true;
                } else {
                    if (warningEdit) warningEdit.style.display = "none";

                    this.classList.remove("is-duplicate");
                    this.style.borderColor = "#e5e7eb";
                    this.style.boxShadow = "none";

                    if (submitBtn) submitBtn.disabled = false;
                }
            });
        }
    });
}

function initDeleteImageModal() {
    const confirmDeleteBtn = document.getElementById("confirmDeleteImageBtn");

    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener("click", function () {
            if (!pendingDeleteImage) return;

            const imageId = pendingDeleteImage.imageId;
            const kamarId = pendingDeleteImage.kamarId;

            const container = document.getElementById(`deleteImagesContainer${kamarId}`);
            const imageElement = document.getElementById(`gallery-item-${imageId}`);

            if (container) {
                const existingInput = container.querySelector(`input[value="${imageId}"]`);

                if (!existingInput) {
                    const input = document.createElement("input");
                    input.type = "hidden";
                    input.name = "delete_images[]";
                    input.value = imageId;
                    container.appendChild(input);
                }
            }

            if (imageElement) {
                imageElement.style.opacity = "0.35";
                imageElement.style.pointerEvents = "none";
                imageElement.style.filter = "grayscale(100%)";
            }

            showNotification(
                "Gambar akan dihapus setelah klik Simpan Perubahan",
                "success"
            );

            const modalElement = document.getElementById("confirmDeleteImageModal");
            const modal = bootstrap.Modal.getInstance(modalElement);

            if (modal) {
                modal.hide();
            }

            pendingDeleteImage = null;
        });
    }
}

// ========== DOM READY ==========

document.addEventListener("DOMContentLoaded", function () {
    initModalReset();
    initFormHandlers();
    initAutoHideAlerts();
    initEventListeners();
    initKamarValidation();
    initDeleteImageModal();

    if (typeof updateFasilitasCount === 'function') {
        updateFasilitasCount();
    }
});

let pendingDeleteImage = null;

window.markDeleteImage = function (event, imageId, kamarId) {
    event.preventDefault();
    event.stopPropagation();

    pendingDeleteImage = {
        imageId: imageId,
        kamarId: kamarId
    };

    const modalElement = document.getElementById("confirmDeleteImageModal");

    if (!modalElement) {
        console.error("Modal confirmDeleteImageModal tidak ditemukan");
        return;
    }

    // Pindahkan modal ke body agar tidak ketutup parent modal
    if (modalElement.parentElement !== document.body) {
        document.body.appendChild(modalElement);
    }

    modalElement.style.zIndex = "2060";

    const modal = bootstrap.Modal.getOrCreateInstance(modalElement, {
        backdrop: true,
        keyboard: false
    });

    modal.show();

    setTimeout(() => {
        const backdrops = document.querySelectorAll(".modal-backdrop");
        const lastBackdrop = backdrops[backdrops.length - 1];

        if (lastBackdrop) {
            lastBackdrop.classList.add("confirm-delete-backdrop");
            lastBackdrop.style.zIndex = "2050";
        }
    }, 50);
};

window.markSetMainImage = function (event, imageId, kamarId) {
    event.preventDefault();
    event.stopPropagation();

    const form = document.getElementById(`formEditKamar${kamarId}`);
    if (!form) return;

    let input = form.querySelector('input[name="set_main_image"]');

    if (!input) {
        input = document.createElement("input");
        input.type = "hidden";
        input.name = "set_main_image";
        form.appendChild(input);
    }

    input.value = imageId;

    const gallery = document.getElementById(`galleryContainer${kamarId}`);
    if (gallery) {
        gallery.querySelectorAll(".gallery-item").forEach((item) => {
            item.style.border = "1px solid #e5e7eb";
        });
    }

    const selectedImage = document.getElementById(`gallery-item-${imageId}`);
    if (selectedImage) {
        selectedImage.style.border = "2px solid #10b981";
    }

    showNotification("Gambar utama akan diganti setelah klik Simpan Perubahan", "success");
};
