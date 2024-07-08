<script>
import Header from "../../components/header.vue";
import DataTable from "../../components/Datatable.vue";
import status from "../../components/status.vue";
import kehadiran from "./aksi/kehadiran.vue";
import approval from "../meeting/approval.vue";
import modalSelectLampiran from "../meeting/detailterlaksana/modalSelectLampiran.vue";
export default {
  components: {
    Header,
    DataTable,
    status,
    kehadiran,
    approval,
    modalSelectLampiran,
  },
  data() {
    return {
      title: "Jadwal Meeting",
      breadcumbs: [
        {
          name: "Beranda",
          link: "#",
        },
        {
          name: "Meeting",
          link: "/meeting/jadwal_meet",
        },
      ],
      dataTable: [],
      search: "",
      searchSelesai: "",
      dataTableSelesai: [],
      headers: [
        { text: "No", value: "no", sortable: false },
        { text: "Nomor Meeting", value: "urutan" },
        { text: "Judul Meeting", value: "judul" },
        { text: "Tanggal", value: "tgl_meet", sortable: false },
        {
          text: "Waktu",
          value: "waktu",
          children: [
            { text: "Mulai", value: "mulai" },
            { text: "Selesai", value: "selesai" },
          ],
        },
        { text: "Lokasi", value: "lokasi" },
        { text: "Status", value: "status" },
        { text: "Kehadiran", value: "status_peserta", sortable: false },
        { text: "Aksi", value: "aksi", sortable: false },
      ],
      filterKehadiran: [],
      tanggalAwal: "",
      tanggalAkhir: "",
      modalKehadiran: false,
      dataKehadiran: null,
      approvalData: [],
      tabs: "belum_selesai",
      showModalCetak: false,
      dataTerlaksana: {},
    };
  },
  methods: {
    clickFilterKehadiran(filter) {
      if (this.filterKehadiran.includes(filter)) {
        this.filterKehadiran = this.filterKehadiran.filter(
          (item) => item !== filter
        );
      } else {
        this.filterKehadiran.push(filter);
      }
    },
    changeLowerAndUnder(text) {
      return text
        .toLowerCase()
        .split("_")
        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
        .join(" ");
    },
    openModalKehadiran(data) {
      this.dataKehadiran = JSON.parse(JSON.stringify(data));
      this.modalKehadiran = true;
      this.$nextTick(() => {
        $(".modalKehadiran").modal("show");
      });
    },
    detail(id, status) {
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
    },
    renderNo(data) {
      return data.map((item, index) => {
        return {
          ...item,
          no: index + 1,
        };
      });
    },
    async getData() {
      try {
        this.tabs = "belum_selesai";
        this.$store.dispatch("setLoading", true);
        const { data: belum } = await this.$_get(
          "/api/hr/meet/jadwal_person/show/belum"
        );

        const { data: selesai } = await this.$_get(
          "/api/hr/meet/jadwal_person/show/selesai/"
        );

        const { data: acc } = await this.$_get(
          "/api/hr/meet/jadwal/show/approval"
        );

        this.dataTable = belum.map((item, index) => {
          return {
            ...item,
            tgl_meet: this.dateFormat(item.tanggal),
            status_peserta:
              item.status_peserta == "belum"
                ? "belum_mengisi_daftar_hadir"
                : item.status_peserta,
          };
        });

        this.dataTableSelesai = selesai.map((item, index) => {
          return {
            ...item,
            tgl_meet: this.dateFormat(item.tanggal),
            status_peserta:
              item.status_peserta == "belum"
                ? "belum_mengisi_daftar_hadir"
                : item.status_peserta,
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
    cetak(data) {
      if (data.status == "terlaksana") {
        this.cetakMeeting(data);
      } else {
        window.open(`/pdfmeet/undangan/${data.id}`, "_blank");
      }
    },
    cetakMeeting(item) {
      this.showModalCetak = true;
      this.dataTerlaksana = item;
      this.$nextTick(() => {
        $(".modalSelectLampiran").modal("show");
      });
    },
  },
  created() {
    this.getData();
  },
  computed: {
    paginateData() {
      let filtered = this.renderNo(this.dataTable);
      if (this.filterKehadiran.length > 0) {
        filtered = this.renderNo(this.dataTable).filter((item) => {
          return this.filterKehadiran.includes(item.status_peserta);
        });
      }

      if (this.tanggalAwal && this.tanggalAkhir) {
        const startDate = new Date(this.tanggalAwal);
        startDate.setHours(0, 0, 0, 0);

        const endDate = new Date(this.tanggalAkhir);
        endDate.setHours(23, 59, 59, 999);

        filtered = this.renderNo(
          filtered.filter((data) => {
            const date = new Date(data.tanggal);
            date.setHours(0, 0, 0, 0);
            return date >= startDate && date <= endDate;
          })
        );
      } else if (this.tanggalAwal) {
        const startDate = new Date(this.tanggalAwal);
        startDate.setHours(0, 0, 0, 0);

        filtered = this.renderNo(
          filtered.filter((data) => {
            const date = new Date(data.tanggal);
            date.setHours(0, 0, 0, 0);
            return date >= startDate;
          })
        );
      } else if (this.tanggalAkhir) {
        const endDate = new Date(this.tanggalAkhir);
        endDate.setHours(23, 59, 59, 999);

        filtered = this.renderNo(
          filtered.filter((data) => {
            const date = new Date(data.tanggal);
            date.setHours(0, 0, 0, 0);
            return date <= endDate;
          })
        );
      }
      return filtered;
    },
    getAllStatusUnique() {
      return this.dataTable
        .map((data) => {
          return data.status_peserta;
        })
        .filter((value, index, self) => {
          return self.indexOf(value) === index;
        });
    },
  },
};
</script>
<template>
  <div>
    <kehadiran
      v-if="modalKehadiran"
      @closeModal="modalKehadiran = false"
      :kehadiran="dataKehadiran"
      @refresh="getData"
    />
    <Header :title="title" :breadcumbs="breadcumbs" />
    <modalSelectLampiran
      v-if="showModalCetak"
      :dokumen="dataTerlaksana.dokumen_meet"
      :id="dataTerlaksana.id"
      @closeModal="showModalCetak = false"
    />
    <div class="card">
      <div class="card-body">
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
          <li class="nav-item" role="presentation">
            <a
              class="nav-link"
              :class="{ active: tabs == 'belum_selesai' }"
              @click="tabs = 'belum_selesai'"
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
              :class="{ active: tabs == 'selesai' }"
              @click="tabs = 'selesai'"
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
          <li class="nav-item" role="presentation">
            <a
              class="nav-link"
              :class="{ active: tabs == 'approval' }"
              @click="tabs = 'approval'"
              id="pills-approval-tab"
              data-toggle="pill"
              data-target="#pills-approval"
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
            <div class="d-flex flex-row-reverse bd-highlight">
              <div class="p-2 bd-highlight">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Cari..."
                  v-model="search"
                />
              </div>
            </div>
            <DataTable
              :headers="headers"
              :items="paginateData"
              :search="search"
            >
              <template #header.tgl_meet>
                <span class="text-bold pr-2">Tanggal Dibuat</span>
                <span class="filter">
                  <a
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                  >
                    <i class="fas fa-filter"></i>
                  </a>
                  <form id="filter_ekat">
                    <div class="dropdown-menu">
                      <div class="px-3 py-3">
                        <div class="row">
                          <div class="col">
                            <div class="form-group">
                              <label for="jenis_penjualan">Tanggal Awal</label>
                              <input
                                type="date"
                                class="form-control"
                                v-model="tanggalAwal"
                                :max="tanggalAkhir"
                              />
                            </div>
                          </div>
                          <div class="col">
                            <div class="form-group">
                              <label for="jenis_penjualan">Tanggal Akhir</label>
                              <input
                                type="date"
                                class="form-control"
                                v-model="tanggalAkhir"
                                :min="tanggalAwal"
                              />
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                </span>
              </template>

              <template #header.status_peserta>
                <span class="text-bold pr-2">Kehadiran</span>
                <span class="filter">
                  <a
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                  >
                    <i class="fas fa-filter"></i>
                  </a>
                  <form id="filter_ekat">
                    <div class="dropdown-menu">
                      <div class="px-3 py-3">
                        <div class="form-group">
                          <label for="jenis_penjualan">Kehadiran</label>
                        </div>
                        <div
                          class="form-group font-weight-normal"
                          v-for="status in getAllStatusUnique"
                          :key="status"
                        >
                          <div class="form-check">
                            <input
                              class="form-check-input"
                              type="checkbox"
                              :ref="status"
                              :value="status"
                              id="status1"
                              @click="clickFilterKehadiran(status)"
                            />
                            <label
                              class="form-check-label text-uppercase"
                              for="status1"
                            >
                              {{ changeLowerAndUnder(status) }}
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                </span>
              </template>

              <template #item.urutan="{ item }">
                <div>
                  {{ item.urutan }} <br />
                  <span class="badge badge-light" v-if="item.is_perubahan">
                    Mengalami Perubahan Jadwal
                  </span>
                </div>
              </template>

              <template #item.status="{ item }">
                <status :status="item.status" />
              </template>
              <template #item.status_peserta="{ item }">
                <status :status="item.status_peserta" />
              </template>
              <template #item.aksi="{ item }">
                <div
                  class="dropdown-toggle"
                  data-toggle="dropdown"
                  id="dropdownMenuButton"
                  aria-haspopup="true"
                  aria-expanded="true"
                >
                  <i class="fas fa-ellipsis-v"></i>
                </div>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <a>
                    <button
                      class="dropdown-item"
                      @click="openModalKehadiran(item)"
                      v-if="
                        item.status_peserta == 'belum_mengisi_daftar_hadir' &&
                        item.status == 'belum'
                      "
                    >
                      <i class="fas fa-check-circle"></i>
                      {{ item.is_perubahan ? "Update Kehadiran" : "Kehadiran" }}
                    </button>
                    <button
                      class="dropdown-item"
                      @click="detail(item.id, item.status)"
                    >
                      <i class="fas fa-eye"></i>
                      Detail
                    </button>
                    <button
                      class="dropdown-item"
                      v-if="item.status == 'terlaksana'"
                    >
                      <i class="fas fa-print"></i>
                      Cetak Hasil Meeting
                    </button>
                  </a>
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
                {{ index + 1 }}
              </template>
              <template #item.no_meet="{ item, index }">
                Meet-{{ index + 1 }}
              </template>
              <template #item.status="{ item }">
                <status :status="item.status" />
              </template>
              <template #item.status_peserta="{ item }">
                <status :status="item.status_peserta" />
              </template>
              <template #item.aksi="{ item }">
                <div
                  class="dropdown-toggle"
                  data-toggle="dropdown"
                  id="dropdownMenuButton"
                  aria-haspopup="true"
                  aria-expanded="true"
                >
                  <i class="fas fa-ellipsis-v"></i>
                </div>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <a>
                    <button
                      class="dropdown-item"
                      @click="detail(item.id, item.status)"
                    >
                      <i class="fas fa-eye"></i>
                      Detail
                    </button>
                    <button
                      class="dropdown-item"
                      v-if="item.status != 'batal'"
                      @click="cetak(item)"
                    >
                      <i class="fas fa-print"></i>
                      {{
                        item.status == "terlaksana"
                          ? "Cetak Hasil Meeting"
                          : "Cetak Undangan Meeting"
                      }}
                    </button>
                  </a>
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
