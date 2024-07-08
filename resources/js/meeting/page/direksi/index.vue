<script>
import Header from "../../components/header.vue";
import DataTable from "../../components/Datatable.vue";
import status from "../../components/status.vue";
export default {
  components: {
    Header,
    DataTable,
    status,
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
        { text: "Mulai", value: "mulai" },
        { text: "Selesai", value: "selesai" },
        { text: "Lokasi", value: "lokasi" },
        { text: "Status", value: "status" },
        { text: "Aksi", value: "aksi", sortable: false },
      ],
      // modal tambah
      modalTambah: false,
      // selesai
      searchSelesai: "",
      dataTableSelesai: [
        {
          no: 1,
          id: 3,
          urutan: "Meet-1",
          judul: "Meeting 2",
          tanggal_meet: "2023-02-01",
          mulai: "08:00",
          selesai: "09:00",
          jumlah_peserta: 10,
          status: "terlaksana",
          lokasi: "Gedung A",
        },
        {
          no: 2,
          id: 4,
          urutan: "Meet-3",
          judul: "Meeting 3",
          tanggal_meet: "2023-02-01",
          mulai: "08:00",
          selesai: "09:00",
          jumlah_peserta: 10,
          status: "batal",
          lokasi: "Gedung A",
        },
      ],
      dataTerlaksana: {},
      modalTerlaksana: false,
      editData: null,
      modalEdit: false,
      modalBatal: false,
      batalData: null,
      cataanData: null,
      modalCatatan: false,
    };
  },
  methods: {
    async getData() {
      try {
        this.$store.dispatch("setLoading", true);
        const { data: belum_terlaksana } = await this.$_get(
          "/api/hr/meet/jadwal/show/belum"
        );
        const { data: selesai } = await this.$_get(
          "/api/hr/meet/jadwal/show/selesai",
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("lokal_token")}`,
            },
          }
        );
        this.dataTable = belum_terlaksana.map((item, index) => {
          return {
            ...item,
            no: index + 1,
            tanggal_meet: this.dateFormat(item.tanggal),
            mulai: this.timeFormat(item.mulai),
            selesai: this.timeFormat(item.selesai),
          };
        });
        this.dataTableSelesai = selesai.map((item, index) => {
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
    detail(id, status) {
      if (status == "terlaksana" || status == "menyusun_hasil_meeting") {
        this.$router.push({
          name: "jadwal-meeting-direksi-detail-terlaksana",
          params: { id: id },
        });
      } else {
        this.$router.push({
          name: "jadwal-meeting-direksi-detail-nonterlaksana",
          params: { id: id },
        });
      }
    },
    cetakUndangan(id) {
      window.open(`/pdfmeet/undangan/${id}`, "_blank");
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
    <div class="card">
      <div class="card-body">
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
          <li class="nav-item" role="presentation">
            <a
              class="nav-link active"
              id="pills-proses-tab"
              data-toggle="pill"
              data-target="#pills-proses"
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
              type="button"
              role="tab"
              aria-controls="pills-selesai"
              aria-selected="false"
              >Selesai</a
            >
          </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
          <div
            class="tab-pane fade show active"
            id="pills-proses"
            role="tabpanel"
            aria-labelledby="pills-proses-tab"
          >
            <div class="d-flex bd-highlight mb-3">
              <div class="mr-auto p-2 bd-highlight"></div>
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
              <template #item.status="{ item }">
                <div>
                  <status :status="item.status" />
                </div>
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
                      type="button"
                      @click="cetakUndangan(item.id)"
                    >
                      <i class="fas fa-print"></i>
                      Cetak Undangan Meeting
                    </button>
                    <button
                      class="dropdown-item"
                      type="button"
                      @click="detail(item.id, item.status)"
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
                        @click="cetak(item)"
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
                      @click="detail(item.id, item.status)"
                    >
                      <i class="fas fa-eye"></i>
                      Detail
                    </button>
                  </div>
                </div>
              </template>
            </DataTable>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
