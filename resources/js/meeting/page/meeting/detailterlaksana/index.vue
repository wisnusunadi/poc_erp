<script>
import Header from "../../../components/header.vue";
import HeaderDetail from "./header.vue";
import Kehadiran from "./kehadiran";
import HasilNotulensi from "./notulen";
import HasilMeeting from "./hasilmeeting";
import DokumenPendukung from "./dokumenpendukung";
import modalSelectLampiran from "./modalSelectLampiran.vue";
export default {
    components: {
        Header,
        HeaderDetail,
        Kehadiran,
        HasilNotulensi,
        HasilMeeting,
        DokumenPendukung,
        modalSelectLampiran,
    },
    data() {
        return {
            title: "Detail Meeting",
            breadcumbs: [
                {
                    name: "Beranda",
                    link: "#",
                },
                {
                    name: "Meeting",
                    link: "/meeting/hr",
                },
                {
                    name: "Detail Meeting",
                    link: "/meeting/hr/detail",
                },
            ],
            meeting: null,
            tabs: "kehadiran",
            showModalCetak: false,
        };
    },
    methods: {
        closeModal() {
            $(".modalDetail").modal("hide");
            this.$nextTick(() => {
                this.$emit("closeModal");
            });
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
        async getData() {
            try {
                this.$store.dispatch("setLoading", true);
                const { data } = await this.$_get(
                    `/api/hr/meet/jadwal/${this.$route.params.id}`
                );
                this.meeting = data;
                this.meeting.dokumen_meet = [
                    {
                        jenis: "foto",
                        dokumen: this.groupDocuments(data.dokumen_meet).foto,
                    },
                    {
                        jenis: "video",
                        dokumen: this.groupDocuments(data.dokumen_meet).video,
                    },
                    {
                        jenis: "rekaman",
                        dokumen: this.groupDocuments(data.dokumen_meet).rekaman,
                    },
                    {
                        jenis: "dokumen",
                        dokumen: this.groupDocuments(data.dokumen_meet).dokumen,
                    },
                ];
            } catch (error) {
                console.log(error);
            } finally {
                this.$store.dispatch("setLoading", false);
            }
        },
        simpanMeeting() {
            console.log("simpan");
            this.$_post(
                    "/api/hr/meet/jadwal/update/selesai",
                    {
                        id: this.$route.params.id,
                        notulensi: this.meeting.hasil_notulen,
                    },
                )
                .then(() => {
                    swal.fire({
                        title: "Berhasil",
                        text: "Data berhasil disimpan",
                        icon: "success",
                    });
                    this.getData();
                })
                .catch(() => {
                    swal.fire({
                        title: "Gagal",
                        text: "Data gagal disimpan",
                        icon: "error",
                    });
                });
        },
        cetakHasilMeet() {
            this.showModalCetak = true;
            this.$nextTick(() => {
                $(".modalSelectLampiran").modal("show");
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
        <modalSelectLampiran
            v-if="showModalCetak"
            :dokumen="meeting.dokumen_meet"
            @closeModal="showModalCetak = false"
        />
        <Header :title="title" :breadcumbs="breadcumbs" />
        <!-- menampilkan data terbaru -->
        <div v-if="!$store.state.loading">
            <HeaderDetail
                :meeting="meeting"
                @simpanMeeting="simpanMeeting"
                @cetakHasilMeet="cetakHasilMeet"
                @refresh="getData"
            />
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a
                        class="nav-link"
                        :class="tabs == 'kehadiran' ? 'active' : ''"
                        @click="tabs = 'kehadiran'"
                        id="pills-kehadiran-tab"
                        data-toggle="pill"
                        data-target="#pills-kehadiran"
                        type="button"
                        role="tab"
                        aria-controls="pills-kehadiran"
                        :aria-selected="tabs == 'kehadiran' ? 'true' : 'false'"
                    >
                        Kehadiran
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a
                        class="nav-link"
                        :class="tabs == 'hasil_notulen' ? 'active' : ''"
                        @click="tabs = 'hasil_notulen'"
                        id="pills-hasil-notulensi-tab"
                        data-toggle="pill"
                        data-target="#pills-hasil-notulensi"
                        type="button"
                        role="tab"
                        aria-controls="pills-hasil-notulensi"
                        :aria-selected="
                            tabs == 'hasil_notulen' ? 'true' : 'false'
                        "
                    >
                        Hasil Notulensi
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a
                        class="nav-link"
                        :class="tabs == 'hasil_meeting' ? 'active' : ''"
                        @click="tabs = 'hasil_meeting'"
                        id="pills-hasil-meeting-tab"
                        data-toggle="pill"
                        data-target="#pills-hasil-meeting"
                        type="button"
                        role="tab"
                        aria-controls="pills-hasil-meeting"
                        :aria-selected="
                            tabs == 'hasil_meeting' ? 'true' : 'false'
                        "
                    >
                        Hasil Meeting
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a
                        class="nav-link"
                        :class="tabs == 'dokumen_pendukung' ? 'active' : ''"
                        @click="tabs = 'dokumen_pendukung'"
                        id="pills-dokumen-pendukung-tab"
                        data-toggle="pill"
                        data-target="#pills-dokumen-pendukung"
                        type="button"
                        role="tab"
                        aria-controls="pills-dokumen-pendukung"
                        :aria-selected="
                            tabs == 'dokumen_pendukung' ? 'true' : 'false'
                        "
                    >
                        Dokumen Pendukung
                    </a>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div
                    class="tab-pane fade"
                    :class="tabs == 'kehadiran' ? 'show active' : ''"
                    id="pills-kehadiran"
                    role="tabpanel"
                    aria-labelledby="pills-kehadiran-tab"
                >
                    <kehadiran :meeting="meeting.riwayat" />
                </div>
                <div
                    class="tab-pane fade"
                    :class="tabs == 'hasil_notulen' ? 'show active' : ''"
                    id="pills-hasil-notulensi"
                    role="tabpanel"
                    aria-labelledby="pills-hasil-notulensi-tab"
                >
                    <hasil-notulensi
                        :meeting="meeting.hasil_notulen"
                        :status="meeting.status"
                        @refresh="getData"
                    />
                </div>
                <div
                    class="tab-pane fade"
                    id="pills-hasil-meeting"
                    role="tabpanel"
                    :class="tabs == 'hasil_meeting' ? 'show active' : ''"
                    aria-labelledby="pills-hasil-meeting-tab"
                >
                    <hasil-meeting
                        :meeting="meeting.hasil_meet"
                        :status="meeting.status"
                        @refresh="getData"
                    />
                </div>
                <div
                    class="tab-pane fade"
                    id="pills-dokumen-pendukung"
                    role="tabpanel"
                    :class="tabs == 'dokumen_pendukung' ? 'show active' : ''"
                    aria-labelledby="pills-dokumen-pendukung-tab"
                >
                    <dokumen-pendukung
                        :meeting="meeting.dokumen_meet"
                        :status="meeting.status"
                        @refresh="getData"
                    />
                </div>
            </div>
        </div>
        <div v-else class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
</template>