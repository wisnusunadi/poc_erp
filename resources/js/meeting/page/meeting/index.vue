<script>
import Header from "../../components/header.vue";
import Tambah from "./aksi/tambah";
import DataTable from "../../components/Datatable.vue";
import status from "../../components/status.vue";
import terlaksana from "./aksi/terlaksana.vue";
import Edit from "./aksi/edit";
import Batal from "./aksi/batal";
import catatan from "./aksi/catatan_peserta";
import uploadTest from "./uploadTest.vue";
import approval from "./approval.vue";
import modalSelectLampiran from "./detailterlaksana/modalSelectLampiran.vue";
import kehadiran from "../peserta/aksi/kehadiran.vue";

export default {
  components: {
    Header,
    Tambah,
    DataTable,
    status,
    terlaksana,
    Edit,
    Batal,
    catatan,
    uploadTest,
    approval,
    modalSelectLampiran,
    kehadiran,
  },
  data() {
    return {
      title: "Meeting",
      breadcumbs: [
        {
          name: "Beranda",
          link: "#",
        },
        {
          name: "Meeting",
          link: "/meeting/hr",
        },
      ],
      search: "",
      dataTable: [],
      headers: [
        { text: "No", value: "no", sortable: false },
        { text: "Nomor Meeting", value: "urutan" },
        { text: "Judul Meeting", value: "judul" },
        { text: "Tanggal", value: "tanggal_meet" },
        {
          text: "Waktu",
          value: "waktu",
          children: [
            { text: "Mulai", value: "mulai" },
            { text: "Selesai", value: "selesai" },
          ],
        },
        { text: "Lokasi", value: "lokasi_nama" },
        { text: "Peran", value: "peran" },
        { text: "Status", value: "status" },
        { text: "Kehadiran", value: "kehadiran" },
        { text: "Aksi", value: "aksi", sortable: false },
      ],
      // modal tambah
      modalTambah: false,
      // selesai
      searchSelesai: "",
      dataTableSelesai: [],
      approvalData: [],
      dataTerlaksana: {},
      modalTerlaksana: false,
      editData: null,
      modalEdit: false,
      modalBatal: false,
      batalData: null,
      cataanData: null,
      modalCatatan: false,
      tabs: "belum_selesai",
      showModalCetak: false,
      dataKehadiran: null,
      modalKehadiran: false,
    };
  },
  methods: {
    async getData() {
      try {
        this.$store.dispatch("setLoading", true);
        this.tabs = "belum_selesai";
        const { data: belum_terlaksana } = await this.$_get(
          "/api/hr/meet/jadwal/show/belum"
        );
        const { data: selesai } = await this.$_get(
          "/api/hr/meet/jadwal/show/selesai"
        );
        const { data: acc } = await this.$_get(
          "/api/hr/meet/jadwal/show/approval"
        );
        this.dataTable = belum_terlaksana.map((item, index) => {
          return {
            ...item,
            no: index + 1,
            tanggal_meet: this.dateFormat(item.tanggal),
            mulai: this.timeFormat(item.mulai),
            selesai: this.timeFormat(item.selesai),
            status_kehadiran:
              item.status_kehadiran == "belum"
                ? "belum_mengisi_daftar_hadir"
                : item.status_kehadiran,
          };
        });
        this.dataTableSelesai = selesai.map((item, index) => {
          return {
            ...item,
            no: index + 1,
            tanggal_meet: this.dateFormat(item.tanggal),
            mulai: this.timeFormat(item.mulai),
            selesai: this.timeFormat(item.selesai),
            dokumen_meet: [
              {
                jenis: "foto",
                dokumen: this.groupDocuments(item.dokumen_meet).foto,
              },
              {
                jenis: "video",
                dokumen: this.groupDocuments(item.dokumen_meet).video,
              },
              {
                jenis: "rekaman",
                dokumen: this.groupDocuments(item.dokumen_meet).rekaman,
              },
              {
                jenis: "dokumen",
                dokumen: this.groupDocuments(item.dokumen_meet).dokumen,
              },
            ],
            status_kehadiran:
              item.status_kehadiran == "belum"
                ? "belum_mengisi_daftar_hadir"
                : item.status_kehadiran,
          };
        });
        this.approvalData = acc.map((item, index) => {
          return {
            ...item,
            no: index + 1,
            tanggal_meet: this.dateFormat(item.tanggal),
            mulai: this.timeFormat(item.mulai),
            selesai: this.timeFormat(item.selesai),
          };
        });
      } catch (error) {
        console.log(error);
      } finally {
        this.$store.dispatch("setLoading", false);
      }
    },
    groupDocuments(documents) {
      const groupedDocuments = {
        foto: [],
        video: [],
        rekaman: [],
        dokumen: [],
      };

      documents.forEach((doc) => {
        const ext = doc.nama.split(".").pop().toLowerCase();

        switch (ext) {
          case "jpg":
          case "jpeg":
          case "png":
            groupedDocuments.foto.push(doc);
            break;
          case "mp4":
          case "avi":
          case "mkv":
            groupedDocuments.video.push(doc);
            break;
          case "mp3":
          case "wav":
          case "mpeg":
            groupedDocuments.rekaman.push(doc);
            break;
          default:
            groupedDocuments.dokumen.push(doc);
            break;
        }
      });

      return groupedDocuments;
    },
    tambahMeeting() {
      this.modalTambah = true;
      this.$nextTick(() => {
        $(".modalMeetingBaru").modal("show");
      });
    },
    closeTambah() {
      this.modalTambah = false;
    },
    showTerlaksana(data) {
      this.dataTerlaksana = JSON.parse(JSON.stringify(data));
      this.modalTerlaksana = true;
      this.$nextTick(() => {
        $(".modalterlaksana").modal("show");
      });
    },
    resetTerlaksana() {
      this.dataTerlaksana = {};
      this.modalTerlaksana = false;
    },
    editMeeting(data) {
      this.editData = JSON.parse(JSON.stringify(data));
      this.modalEdit = true;
      this.$nextTick(() => {
        $(".modalMeetingEdit").modal("show");
      });
    },
    resetEdit() {
      this.editData = null;
      this.modalEdit = false;
    },
    batalMeeting(data) {
      this.batalData = data;
      this.modalBatal = true;
      this.$nextTick(() => {
        $(".modalBatal").modal("show");
      });
    },
    resetBatal() {
      this.batalData = null;
      this.modalBatal = false;
    },
    catatanPeserta(data) {
      this.catatanData = data;
      this.modalCatatan = true;
      this.$nextTick(() => {
        $(".modalCatatan").modal("show");
      });
    },
    resetCatatan() {
      this.catatanData = null;
      this.modalCatatan = false;
    },
    detail({ id, status, peran }) {
      if (peran.includes("notulen")) {
        if (
          status == "terlaksana" ||
          status == "menyusun_hasil_meeting" ||
          status == "menunggu_approval_pimpinan"
        ) {
          this.$router.push({
            name: "detail-meeting-terlaksana",
            params: { id: id },
          });
        } else {
          this.$router.push({
            name: "detail-meeting-nonterlaksana",
            params: { id: id },
          });
        }
      } else {
        if (
          status == "terlaksana" ||
          status == "menyusun_hasil_meeting" ||
          status == "menunggu_approval_pimpinan"
        ) {
          this.$router.push({
            name: "jadwal-meeting-peserta-detail-terlaksana",
            params: { id: id },
          });
        } else {
          this.$router.push({
            name: "jadwal-meeting-peserta-detail-nonterlaksana",
            params: { id: id },
          });
        }
      }
    },
    cetakUndangan(id) {
      window.open(`/pdfmeet/undangan/${id}`, "_blank");
    },
    cetakMeeting(item) {
      this.showModalCetak = true;
      this.dataTerlaksana = item;
      this.$nextTick(() => {
        $(".modalSelectLampiran").modal("show");
      });
    },
    openModalKehadiran(data) {
      console.log(data);
      this.dataKehadiran = JSON.parse(JSON.stringify(data));
      this.modalKehadiran = true;
      this.$nextTick(() => {
        $(".modalKehadiran").modal("show");
      });
    },
    approve({ id, hasil_notulen }) {
      swal
        .fire({
          title: "Setujui?",
          text: "Apakah anda yakin ingin menyetujui data ini?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Ya",
          cancelButtonText: "Tidak",
        })
        .then((result) => {
          if (result.isConfirmed) {
            this.$_post("/api/hr/meet/jadwal/update/approve_setuju", {
              id,
              notulensi: hasil_notulen,
            })
              .then((res) => {
                swal.fire("Berhasil!", "Data telah disetujui.", "success");
                this.getData();
              })
              .catch((err) => {
                swal.fire("Gagal!", "Data gagal disetujui.", "error");
              });
          }
        });
    },
    reject(id) {
      swal
        .fire({
          title: "Tolak?",
          text: "Apakah anda yakin ingin menolak data ini?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Ya",
          cancelButtonText: "Tidak",
        })
        .then((result) => {
          if (result.isConfirmed) {
            axios
              .$_post("/api/hr/meet/jadwal/update/approve_batal", {
                id,
              })
              .then((res) => {
                swal.fire("Berhasil!", "Data telah ditolak.", "success");
                this.getData();
              })
              .catch((err) => {
                swal.fire("Gagal!", "Data gagal ditolak.", "error");
              });
          }
        });
    },
  },
  created() {
    this.getData();
  },
};
</script>
<template>
  <div>
    <Header :title="title" :breadcumbs="breadcumbs" />
    <kehadiran
      v-if="modalKehadiran"
      @closeModal="modalKehadiran = false"
      :kehadiran="dataKehadiran"
      @refresh="getData"
    />
    <modalSelectLampiran
      v-if="showModalCetak"
      :dokumen="dataTerlaksana.dokumen_meet"
      :id="dataTerlaksana.id"
      @closeModal="showModalCetak = false"
    />
    <Tambah v-if="modalTambah" @closeModal="closeTambah" @refresh="getData" />
    <Batal
      :meeting="batalData"
      v-if="modalBatal"
      @closeModal="resetBatal"
      @refresh="getData"
    />
    <Edit
      :meeting="editData"
      v-if="modalEdit"
      @closeModal="resetEdit"
      @refresh="getData"
    />
    <terlaksana
      :meeting="dataTerlaksana"
      v-if="modalTerlaksana"
      @closeModal="resetTerlaksana"
      @refresh="getData"
    />
    <catatan
      :meeting="catatanData"
      v-if="modalCatatan"
      @closeModal="resetCatatan"
    />
    <div class="card">
      <div class="card-body">
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
          <li class="nav-item" role="presentation">
            <a
              class="nav-link"
              :class="tabs == 'belum_selesai' ? 'active' : ''"
              id="pills-proses-tab"
              data-toggle="pill"
              data-target="#pills-proses"
              @click="tabs = 'belum_selesai'"
              type="button"
              role="tab"
              aria-controls="pills-proses"
              aria-selected="true"
              >Belum Selesai</a
            >
          </li>
          <li class="nav-item" role="presentation">
            <a
              class="nav-link"
              id="pills-selesai-tab"
              data-toggle="pill"
              data-target="#pills-selesai"
              :class="tabs == 'selesai' ? 'active' : ''"
              @click="tabs = 'selesai'"
              type="button"
              role="tab"
              aria-controls="pills-selesai"
              aria-selected="false"
              >Selesai</a
            >
          </li>
          <li class="nav-item" role="presentation">
            <a
              class="nav-link"
              id="pills-approval-tab"
              data-toggle="pill"
              data-target="#pills-approval"
              :class="tabs == 'approval' ? 'active' : ''"
              @click="tabs = 'approval'"
              v-if="approvalData.length > 0"
              type="button"
              role="tab"
              aria-controls="pills-approval"
              aria-selected="false"
              >Approval</a
            >
          </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
          <div
            class="tab-pane fade"
            :class="tabs == 'belum_selesai' ? 'show active' : ''"
            id="pills-proses"
            role="tabpanel"
            aria-labelledby="pills-proses-tab"
          >
            <div class="d-flex bd-highlight mb-3">
              <div class="mr-auto p-2 bd-highlight">
                <button class="btn btn-primary" @click="tambahMeeting">
                  <i class="fa fa-plus"></i>
                  Tambah
                </button>
              </div>
              <div class="p-2 bd-highlight">
                <div class="input-group">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Cari..."
                    v-model="search"
                  />
                </div>
              </div>
            </div>

            <DataTable :headers="headers" :items="dataTable" :search="search">
              <template #item.urutan="{ item }">
                <div>
                  {{ item.urutan }} <br />
                  <span class="badge badge-light" v-if="item.is_perubahan">
                    Mengalami Perubahan Jadwal
                  </span>
                </div>
              </template>
              <template #item.status="{ item }">
                <div>
                  <status :status="item.status" />
                </div>
              </template>
              <template #item.peran="{ item }">
                <div>
                  <span v-for="(peran, index) in item.peran" :key="index">
                    {{ peran
                    }}<span v-if="index != item.peran.length - 1">,</span>
                  </span>
                </div>
              </template>
              <template #item.kehadiran="{ item }">
                <status :status="item.status_kehadiran" />
              </template>
              <template #item.aksi="{ item }">
                <div class="dropdown">
                  <div
                    class="dropdown-toggle"
                    data-toggle="dropdown"
                    aria-expanded="false"
                  >
                    <i class="fas fa-ellipsis-v"></i>
                  </div>
                  <div class="dropdown-menu">
                    <button
                      class="dropdown-item"
                      @click="openModalKehadiran(item)"
                      v-if="
                        item.status_kehadiran == 'belum_mengisi_daftar_hadir' &&
                        item.status == 'belum' &&
                        !item.peran.includes('notulen')
                      "
                    >
                      <i class="fas fa-check-circle"></i>
                      {{ item.is_perubahan ? "Update Kehadiran" : "Kehadiran" }}
                    </button>
                    <button
                      class="dropdown-item"
                      type="button"
                      @click="cetakUndangan(item.id)"
                      v-if="item.status == 'belum'"
                    >
                      <i class="fas fa-print"></i>
                      Cetak Undangan Meeting
                    </button>
                    <button
                      class="dropdown-item"
                      type="button"
                      @click="showTerlaksana(item)"
                      v-if="
                        item.status == 'belum' &&
                        !item.peran.includes('peserta')
                      "
                    >
                      <i class="fas fa-check"></i>
                      Terlaksana
                    </button>
                    <button
                      class="dropdown-item"
                      type="button"
                      @click="detail(item)"
                    >
                      <i class="fas fa-eye"></i>
                      Detail
                    </button>
                    <button
                      class="dropdown-item"
                      type="button"
                      @click="editMeeting(item)"
                      v-if="
                        item.status == 'belum' &&
                        !item.peran.includes('peserta')
                      "
                    >
                      <i class="fas fa-edit"></i>
                      Edit
                    </button>
                    <button
                      class="dropdown-item"
                      type="button"
                      @click="batalMeeting(item)"
                      v-if="
                        item.status == 'belum' &&
                        !item.peran.includes('peserta')
                      "
                    >
                      <i class="fas fa-times"></i>
                      Batal
                    </button>
                    <!-- <button v-if="item.status == 'menyusun_hasil_meeting'" class="dropdown-item"
                                            @click="catatanPeserta(item)">
                                            <i class="fas fa-edit"></i>
                                            Catatan Peserta
                                        </button> -->
                    <button
                      class="dropdown-item"
                      v-if="item.status == 'terlaksana'"
                      @click="cetakMeeting(item)"
                    >
                      <i class="fas fa-print"></i>
                      Cetak Hasil Meeting
                    </button>
                    <button
                      class="dropdown-item"
                      type="button"
                      v-if="
                        item.status == 'menunggu_approval_pimpinan' &&
                        item.peran.includes('pimpinan')
                      "
                      @click="approve(item)"
                    >
                      <i class="fas fa-check"></i>
                      Setujui
                    </button>
                    <button
                      class="dropdown-item"
                      type="button"
                      v-if="
                        item.status == 'menunggu_approval_pimpinan' &&
                        item.peran.includes('pimpinan')
                      "
                      @click="reject(item.id)"
                    >
                      <i class="fas fa-times"></i>
                      Tolak
                    </button>
                  </div>
                </div>
              </template>
            </DataTable>
          </div>
          <div
            class="tab-pane fade"
            :class="tabs == 'selesai' ? 'show active' : ''"
            id="pills-selesai"
            role="tabpanel"
            aria-labelledby="pills-selesai-tab"
          >
            <div class="d-flex bd-highlight mb-3">
              <div class="mr-auto p-2 bd-highlight"></div>
              <div class="p-2 bd-highlight">
                <div class="input-group">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Cari..."
                    v-model="searchSelesai"
                  />
                </div>
              </div>
            </div>

            <DataTable
              :headers="headers"
              :items="dataTableSelesai"
              :search="searchSelesai"
            >
              <template #item.no="{ item, index }">
                <div>
                  {{ index + 1 }}
                </div>
              </template>
              <template #item.no_meet="{ item, index }">
                <div>Meet-{{ index + 1 }}</div>
              </template>
              <template #item.status="{ item }">
                <div>
                  <status :status="item.status" />
                </div>
              </template>
              <template #item.peran="{ item }">
                <div>
                  <span v-for="(peran, index) in item.peran" :key="index">
                    {{ peran
                    }}<span v-if="index != item.peran.length - 1">,</span>
                  </span>
                </div>
              </template>
              <template #item.kehadiran="{ item }">
                <status :status="item.status_kehadiran" />
              </template>
              <template #item.aksi="{ item }">
                <div>
                  <div
                    class="dropdown-toggle"
                    data-toggle="dropdown"
                    id="dropdownMenuButton"
                    aria-haspopup="true"
                    aria-expanded="true"
                  >
                    <i class="fas fa-ellipsis-v"></i>
                  </div>
                  <div
                    class="dropdown-menu"
                    aria-labelledby="dropdownMenuButton"
                  >
                    <a>
                      <button
                        class="dropdown-item"
                        type="button"
                        @click="cetakMeeting(item)"
                        v-if="item.status != 'batal'"
                      >
                        <i class="fas fa-print"></i>
                        {{
                          item.status == "terlaksana"
                            ? "Cetak Hasil Meeting"
                            : "Cetak Undangan Meeting"
                        }}
                      </button>
                    </a>
                    <button
                      class="dropdown-item"
                      type="button"
                      @click="detail(item)"
                    >
                      <i class="fas fa-eye"></i>
                      Detail
                    </button>
                  </div>
                </div>
              </template>
            </DataTable>
          </div>
          <div
            class="tab-pane fade"
            :class="tabs == 'approval' ? 'show active' : ''"
            id="pills-approval"
            role="tabpanel"
            aria-labelledby="pills-approval-tab"
            v-if="approvalData.length > 0"
          >
            <approval
              :approvalData="approvalData"
              @detail="detail"
              @refresh="getData"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
