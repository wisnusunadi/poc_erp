<script>
import uploadFile from "../../../../components/uploadFile.vue";
export default {
  components: {
    uploadFile,
  },
  data() {
    return {
      file: [],
      loading: false,
    };
  },
  methods: {
    save() {
      this.loading = true;
      // detected form data is empty or not this.form.file = null
      if (this.file == null) {
        this.$swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Dokumen Pendukung tidak boleh kosong!",
        });
        return;
      }

      let formData = new FormData();

      for (let i = 0; i < this.file.length; i++) {
        formData.append("dokumentasi[]", this.file[i]);
      }

      formData.append("id", this.$route.params.id);

      this.$_post("/api/hr/meet/hasil/dokumen", formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          this.$swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Dokumen Pendukung berhasil disimpan",
          });
          this.$emit("refresh");
          this.resetForm();
          this.closeModal();
        })
        .catch((error) => {
          this.$swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Gagal menyimpan Dokumen Pendukung",
          });
        })
        .finally(() => {
          this.loading = false;
        });
    },
    closeModal() {
      $(".modalDokumenPendukung").modal("hide");
      this.$nextTick(() => {
        this.$emit("closeModal");
      });
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
          <button
            type="button"
            :disabled="loading"
            class="close"
            @click="closeModal"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" v-if="!loading">
          <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">Dokumentasi</label>
            <uploadFile @changed="file = $event" />
          </div>
        </div>
        <div class="modal-body text-center" v-else>
          <div class="d-flex justify-content-center">
            <script
              src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs"
              type="module"
            ></script>
            <dotlottie-player
              src="https://lottie.host/19bf105b-8bab-4166-a398-5737953bf1e0/YXTnQ5N482.json"
              background="transparent"
              speed="1"
              style="width: 300px; height: 300px"
              loop
              autoplay
            ></dotlottie-player>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            :disabled="loading"
            @click="closeModal"
          >
            Keluar
          </button>
          <button
            type="button"
            class="btn btn-primary"
            @click="save"
            :disabled="loading"
          >
            <div
              class="spinner-border spinner-border-sm"
              role="status"
              v-if="loading"
            >
              <span class="sr-only">Loading...</span>
            </div>
            {{ loading ? "Menyimpan..." : "Simpan" }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
