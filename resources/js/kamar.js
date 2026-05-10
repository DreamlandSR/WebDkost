let selectedFilesDataTransfer = new DataTransfer();

function previewMultipleImages(event) {
    let files = [];

    if (event && event.target && event.target.files) {
        files = Array.from(event.target.files);
    } else if (event && event instanceof FileList) {
        files = Array.from(event);
    } else if (event && event.length !== undefined) {
        files = Array.from(event);
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

    const validFiles = files.filter((file) => {
        const isValidType = validTypes.includes(file.type);
        if (!isValidType) {
            showNotification(
                `File "${file.name}" bukan format gambar yang didukung`,
                "error",
            );
            return false;
        }

        if (file.size > maxSize) {
            showNotification(
                `Ukuran file "${file.name}" melebihi batas 2MB`,
                "error",
            );
            return false;
        }

        return true;
    });

    validFiles.forEach((file) => {
        selectedFilesDataTransfer.items.add(file);
    });

    renderPreviewImages();
}

window.removeImageTambah = function (index) {
    const newDt = new DataTransfer();
    const files = selectedFilesDataTransfer.files;

    for (let i = 0; i < files.length; i++) {
        if (i !== index) {
            newDt.items.add(files[i]);
        }
    }

    selectedFilesDataTransfer = newDt;
    renderPreviewImages();
};

function renderPreviewImages() {
    const preview = document.getElementById("imagePreview");
    const uploadLabel = document.getElementById("imageUploadLabel");
    const fileInput = document.getElementById("imageInput");

    if (!preview || !uploadLabel || !fileInput) return;

    // Update the actual input files
    fileInput.files = selectedFilesDataTransfer.files;

    preview.innerHTML = "";
    const files = selectedFilesDataTransfer.files;

    if (files.length === 0) {
        fileInput.value = "";
        preview.style.display = "none";
        uploadLabel.style.display = "block";
        return;
    }

    uploadLabel.style.display = "none";
    preview.style.display = "flex";

    Array.from(files).forEach((file, index) => {
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
                <button type="button" onclick="window.removeImageTambah(${index})" style="position: absolute; top: 4px; right: 4px; background: rgba(239, 68, 68, 0.9); color: white; border: none; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 10px; z-index: 10; transition: background 0.2s;" onmouseover="this.style.background='#dc2626'" onmouseout="this.style.background='rgba(239, 68, 68, 0.9)'" title="Hapus Gambar">
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
        transition: all 0.2s;
        color: #00a669;
        margin: 0;
    `;
    addMoreContainer.onmouseover = function () {
        this.style.background = "#d1fae5";
    };
    addMoreContainer.onmouseout = function () {
        this.style.background = "#ecfdf5";
    };
    addMoreContainer.innerHTML = `
        <i class="ti-plus" style="font-size: 24px; margin-bottom: 4px;"></i>
        <span style="font-size: 11px; font-weight: 600;">Tambah</span>
    `;
    preview.appendChild(addMoreContainer);
}

let selectedFilesEditDataTransfers = {};

/**
 * Preview multiple images for EDIT modal
 */
function previewMultipleImagesEdit(event, id) {
    let files = [];
    if (event && event.target && event.target.files) {
        files = Array.from(event.target.files);
    } else if (event && event instanceof FileList) {
        files = Array.from(event);
    } else if (event && event.length !== undefined) {
        files = Array.from(event);
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
    const maxSize = 2 * 1024 * 1024; // 2MB

    const validFiles = files.filter((file) => {
        const isValidType = validTypes.includes(file.type);
        if (!isValidType) {
            showNotification(
                `File "${file.name}" bukan format gambar yang didukung`,
                "error",
            );
            return false;
        }

        if (file.size > maxSize) {
            showNotification(
                `Ukuran file "${file.name}" melebihi batas 2MB`,
                "error",
            );
            return false;
        }

        return true;
    });

    if (!selectedFilesEditDataTransfers[id]) {
        selectedFilesEditDataTransfers[id] = new DataTransfer();
    }

    validFiles.forEach((file) => {
        selectedFilesEditDataTransfers[id].items.add(file);
    });

    renderPreviewImagesEdit(id);
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

// ========== AJAX FUNCTIONS ==========

/**
 * Delete image via AJAX
 */
window.deleteImage = async function deleteImage(imageId, kamarId) {
    if (!confirm("Yakin ingin menghapus gambar ini?")) return;

    try {
        const csrfToken =
            document.querySelector('meta[name="csrf-token"]')?.content || "";

        const response = await fetch(`/kamar/image/${imageId}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "Content-Type": "application/json",
            },
        });

        const data = await response.json();

        if (data.success) {
            const imageElement = document.getElementById(
                `gallery-item-${imageId}`,
            );
            if (imageElement) imageElement.remove();

            const container = document.getElementById(
                `deleteImagesContainer${kamarId}`,
            );
            if (container) {
                const hiddenInput = document.createElement("input");
                hiddenInput.type = "hidden";
                hiddenInput.name = "delete_images[]";
                hiddenInput.value = imageId;
                container.appendChild(hiddenInput);
            }

            showNotification("Gambar berhasil dihapus", "success");
        } else {
            showNotification("Gagal menghapus gambar", "error");
        }
    } catch (error) {
        console.error("Error:", error);
        showNotification("Terjadi kesalahan", "error");
    }
}

/**
 * Set main image via AJAX
 */
async function setMainImage(imageId, kamarId) {
    try {
        const csrfToken =
            document.querySelector('meta[name="csrf-token"]')?.content || "";

        const response = await fetch(`/kamar/image/${imageId}/main`, {
            method: "PUT",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "Content-Type": "application/json",
            },
        });

        const data = await response.json();

        if (data.success) {
            location.reload();
        } else {
            showNotification("Gagal mengubah gambar utama", "error");
        }
    } catch (error) {
        console.error("Error:", error);
        showNotification("Terjadi kesalahan", "error");
    }
}

// ========== NOTIFICATION SYSTEM ==========

/**
 * Show notification toast
 */
function showNotification(message, type = "success") {
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
}

// ========== FORM PILL SELECTORS ==========

/**
 * Select tipe pill in create modal
 */
// file: kamar.js atau di dalam tag <script>

// WAJIB menggunakan window. agar bisa dipanggil oleh onchange="..." di HTML
window.selectTipe = function (radio) {
    const tipeGrid = document.getElementById("tipeGrid");
    const errorTipe = document.getElementById("error-tipe-js");

    // Hapus efek error
    if (tipeGrid) {
        tipeGrid.style.background = "transparent";
        tipeGrid.style.boxShadow = "none";
        tipeGrid.style.padding = "0";
    }
    if (errorTipe) errorTipe.style.display = "none";

    // Reset warna semua pill tipe
    document
        .querySelectorAll("#tambahKamarModal .tipe-pill .pill-label")
        .forEach((el) => {
            el.style.background = "#f9fafb";
            el.style.color = "#6b7280";
            el.style.borderColor = "#e5e7eb";
            el.style.fontWeight = "500";
        });

    // Beri warna hijau pada pill yang dipilih
    const lbl = radio.nextElementSibling;
    if (lbl) {
        lbl.style.background = "#ecfdf5";
        lbl.style.color = "#00a669";
        lbl.style.borderColor = "#00a669";
        lbl.style.fontWeight = "600";
    }
};

window.selectStatus = function (radio) {
    const statusGrid = document.getElementById("statusGrid");
    const errorStatus = document.getElementById("error-status-js");

    // Hapus efek error
    if (statusGrid) {
        statusGrid.style.background = "transparent";
        statusGrid.style.boxShadow = "none";
        statusGrid.style.padding = "0";
    }
    if (errorStatus) errorStatus.style.display = "none";

    // Reset warna semua pill status
    document
        .querySelectorAll("#tambahKamarModal .status-pill .pill-label")
        .forEach((el) => {
            el.style.background = "#f9fafb";
            el.style.color = "#6b7280";
            el.style.borderColor = "#e5e7eb";
            el.style.fontWeight = "500";
        });

    // Beri warna hijau pada pill yang dipilih
    const lbl = radio.nextElementSibling;
    if (lbl) {
        lbl.style.background = "#ecfdf5";
        lbl.style.color = "#00a669";
        lbl.style.borderColor = "#00a669";
        lbl.style.fontWeight = "600";
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
            const errorMessages = ["error-tipe-js", "error-status-js"];
            errorMessages.forEach((id) => {
                const el = document.getElementById(id);
                if (el) el.style.display = "none";
            });

            // 4. Reset Gaya Visual Pill (Kembali ke Abu-abu)
            document
                .querySelectorAll("#tambahKamarModal .pill-label")
                .forEach((el) => {
                    el.style.background = "#f9fafb";
                    el.style.color = "#6b7280";
                    el.style.borderColor = "#e5e7eb";
                    el.style.fontWeight = "500";
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
            selectedFilesDataTransfer = new DataTransfer();
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
                fileInput.files = selectedFilesDataTransfer.files;
            }

            console.log("Modal Tambah Kamar berhasil dibersihkan.");
        });
    }

    // --- RESET UNTUK MODAL EDIT (Jika Anda memiliki banyak modal edit) ---
    document.querySelectorAll('.modal[id^="editModal"]').forEach((modal) => {
        modal.addEventListener("hidden.bs.modal", function () {
            const kamarId = this.id.replace("editModal", "");

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

// Reset edit modals newly added images on close
document.querySelectorAll('.modal[id^="editModal"]').forEach((modal) => {
    modal.addEventListener("hidden.bs.modal", function () {
        const kamarId = this.id.replace("editModal", "");

        // Reset newly added images if they exist
        if (selectedFilesEditDataTransfers[kamarId]) {
            selectedFilesEditDataTransfers[kamarId] = new DataTransfer();
            renderPreviewImagesEdit(kamarId);
        }
    });
});

// ========== FORM SUBMIT HANDLERS ==========

/**
 * Initialize form submit handlers
 */
function initFormHandlers() {
    // Hanya target form create/edit kamar, BUKAN form search/filter
    const kamarForms = [
        ...document.querySelectorAll('form[id="formTambahKamar"]'),
        ...document.querySelectorAll('form[id^="formEditKamar"]'),
    ];
    kamarForms.forEach((form) => {
        form.addEventListener("submit", function (e) {
            // 1. Ambil data radio yang terpilih
            const tipeTerpilih = this.querySelector(
                'input[name="tipe_kamar"]:checked',
            );
            const statusTerpilih = this.querySelector(
                'input[name="status_kamar"]:checked',
            );

            let isValid = true;

            // 2. Validasi Tipe Kamar
            const tipeGrid = document.getElementById("tipeGrid");
            const errorTipe = document.getElementById("error-tipe-js");
            if (!tipeTerpilih) {
                if (tipeGrid) {
                    tipeGrid.style.background = "linear-gradient(135deg, #fff5f5, #fee2e2)";
                    tipeGrid.style.boxShadow = "0 0 0 2px #f87171, 0 4px 12px rgba(239,68,68,0.12)";
                    tipeGrid.style.borderRadius = "10px";
                    tipeGrid.style.padding = "10px";
                    tipeGrid.style.transition = "all 0.25s ease";
                }
                if (errorTipe) errorTipe.style.display = "block";
                isValid = false;
            } else {
                if (tipeGrid) {
                    tipeGrid.style.background = "transparent";
                    tipeGrid.style.boxShadow = "none";
                    tipeGrid.style.padding = "0";
                }
                if (errorTipe) errorTipe.style.display = "none";
            }

            // 3. Validasi Status Kamar
            const statusGrid = document.getElementById("statusGrid");
            const errorStatus = document.getElementById("error-status-js");
            if (!statusTerpilih) {
                if (statusGrid) {
                    statusGrid.style.background = "linear-gradient(135deg, #fff5f5, #fee2e2)";
                    statusGrid.style.boxShadow = "0 0 0 2px #f87171, 0 4px 12px rgba(239,68,68,0.12)";
                    statusGrid.style.borderRadius = "10px";
                    statusGrid.style.padding = "10px";
                    statusGrid.style.transition = "all 0.25s ease";
                }
                if (errorStatus) errorStatus.style.display = "block";
                isValid = false;
            } else {
                if (statusGrid) {
                    statusGrid.style.background = "transparent";
                    statusGrid.style.boxShadow = "none";
                    statusGrid.style.padding = "0";
                }
                if (errorStatus) errorStatus.style.display = "none";
            }

            // 4. Jika tidak valid, hentikan proses
            if (!isValid) {
                e.preventDefault();
                showNotification(
                    "Harap lengkapi Tipe dan Status Kamar!",
                    "error",
                );
                return false;
            }

            // 5. Jika valid, jalankan efek loading tombol (kode asli Anda)
            const submitButton = this.querySelector('button[type="submit"]');
            if (submitButton && !submitButton.disabled) {
                submitButton.disabled = true;
                submitButton.innerHTML =
                    '<i class="ti-reload" style="animation: spin 1s linear infinite;"></i> Menyimpan...';
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
    // File input untuk CREATE modal
    const createFileInput = document.getElementById("imageInput");
    if (createFileInput) {
        createFileInput.addEventListener("change", function (e) {
            if (e.target.files && e.target.files.length > 0) {
                previewMultipleImages(e);
            } else {
                // If user cancelled, restore the input from our DataTransfer
                this.files = selectedFilesDataTransfer.files;
            }
        });
    }

    // File input untuk EDIT modals
    document.querySelectorAll('input[id^="imageInput"]').forEach((input) => {
        if (input.id !== "imageInput") {
            const kamarId = input.id.replace("imageInput", "");
            input.addEventListener("change", function (e) {
                if (e.target.files && e.target.files.length > 0) {
                    previewMultipleImagesEdit(e, kamarId);
                } else {
                    if (selectedFilesEditDataTransfers[kamarId]) {
                        this.files =
                            selectedFilesEditDataTransfers[kamarId].files;
                    }
                }
            });
        }
    });

    // Delete image buttons
    document.querySelectorAll(".btn-delete-image").forEach((btn) => {
        btn.addEventListener("click", function () {
            const imageId = this.dataset.id;
            const kamarId = this.dataset.kamar;
            deleteImage(imageId, kamarId);
        });
    });

    // Set main image buttons
    document.querySelectorAll(".btn-set-main").forEach((btn) => {
        btn.addEventListener("click", function () {
            const imageId = this.dataset.id;
            const kamarId = this.dataset.kamar;
            setMainImage(imageId, kamarId);
        });
    });
}

/**
 * Initialize validation for nomor kamar
 */
function initKamarValidation() {
    // For Add Modal
    const formTambahKamar = document.getElementById("formTambahKamar");
    if (formTambahKamar) {
        const inputNomor = formTambahKamar.querySelector(
            'input[name="nomor_kamar"]',
        );
        if (inputNomor) {
            inputNomor.addEventListener("input", function () {
                const val = this.value.trim().toLowerCase();
                if (
                    window.existingKamarNumbers &&
                    window.existingKamarNumbers.some(
                        (n) => n.toLowerCase() === val,
                    )
                ) {
                    showNotification(
                        "Nomor kamar sudah terisi/digunakan!",
                        "error",
                    );
                    this.style.borderColor = "#ef4444";
                    this.style.boxShadow = "0 0 0 3px rgba(239,68,68,0.1)";
                    const submitBtn = formTambahKamar.querySelector(
                        'button[type="submit"]',
                    );
                    if (submitBtn) submitBtn.disabled = true;
                } else {
                    this.style.borderColor = "#e5e7eb";
                    this.style.boxShadow = "none";
                    const submitBtn = formTambahKamar.querySelector(
                        'button[type="submit"]',
                    );
                    if (submitBtn) submitBtn.disabled = false;
                }
            });
        }
    }

    // For Edit Modals
    document.querySelectorAll('form[id^="formEditKamar"]').forEach((form) => {
        const inputNomor = form.querySelector('input[name="nomor_kamar"]');

        if (inputNomor && !inputNomor.hasAttribute("data-original")) {
            inputNomor.setAttribute(
                "data-original",
                inputNomor.value.trim().toLowerCase(),
            );
        }

        if (inputNomor) {
            inputNomor.addEventListener("input", function () {
                const val = this.value.trim().toLowerCase();
                const originalVal = this.getAttribute("data-original");

                const isTaken =
                    window.existingKamarNumbers &&
                    window.existingKamarNumbers.some(
                        (n) => n.toLowerCase() === val,
                    ) &&
                    val !== originalVal;

                if (isTaken) {
                    showNotification(
                        "Nomor kamar sudah terisi/digunakan!",
                        "error",
                    );
                    this.style.borderColor = "#ef4444";
                    this.style.boxShadow = "0 0 0 3px rgba(239,68,68,0.1)";
                    const submitBtn = form.querySelector(
                        'button[type="submit"]',
                    );
                    if (submitBtn) submitBtn.disabled = true;
                } else {
                    this.style.borderColor = "#e5e7eb";
                    this.style.boxShadow = "none";
                    const submitBtn = form.querySelector(
                        'button[type="submit"]',
                    );
                    if (submitBtn) submitBtn.disabled = false;
                }
            });
        }
    });
}

// ========== DOM READY ==========

document.addEventListener("DOMContentLoaded", function () {
    initModalReset();
    initFormHandlers();
    initAutoHideAlerts();
    initEventListeners();
    initKamarValidation();
});
