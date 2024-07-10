<script>
import uploadFile from "../../../../components/uploadFile.vue";
export default {
  components: {
    uploadFile,
  },
  data() {
    return {
      file: [],
    };
  },
  methods: {
    save() {
      // detected form data is empty or not this.form.file = null
      if (this.form.file == null) {
        this.$swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Dokumen Pendukung tidak boleh kosong!",
        });
        return;
      }
      this.closeModal();
    },
    closeModal() {
      this.$nextTick(() => {
        $(".modalDokumenPendukung").modal("hide");
      });
      this.$emit("closeModal");
    },
    resetForm() {
      this.file = [];
    },
  },
  mounted() {
    this.resetForm();
  },
};
</script>
<template>
  <div
    class="modal fade modalDokumenPendukung"
    data-backdrop="static"
    data-keyboard="false"
    tabindex="-1"
    aria-labelledby="staticBackdropLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Dokumen Pendukung</h5>
          <button type="button" class="close" @click="closeModal">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">Dokumentasi</label>
            <uploadFile @changed="file = $event" />
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeModal">
            Keluar
          </button>
          <button type="button" class="btn btn-primary" @click="save">
            Simpan
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
