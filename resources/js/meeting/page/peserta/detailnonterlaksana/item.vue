<script>
import kehadiran from "../../../components/kehadiran.vue";
import DataTable from "../../../components/Datatable.vue";
export default {
  components: {
    DataTable,
    kehadiran,
  },
  data() {
    return {
      search: "",
      headers: [
        { text: "Nama Peserta", value: "nama" },
        { text: "Divisi", value: "jabatan" },
        { text: "Kehadiran", value: "status" },
        { text: "Alasan", value: "ket" },
        { text: "Dokumen Pendukung Ketidakhadiran", value: "dokumen" },
      ],
    };
  },
  props: {
    meeting: {
      type: Array,
      default: () => [],
    },
  },
};
</script>
<template>
  <div class="card">
    <div class="card-body">
      <h4 class="mb-4">Feedback Peserta (Kehadiran)</h4>
      <div class="d-flex flex-row-reverse bd-highlight">
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
      <DataTable :headers="headers" :items="meeting" :search="search">
        <template #item.status="{ item }">
          <div>
            <kehadiran :kehadiran="item.status"></kehadiran>
          </div>
        </template>
        <template #item.dokumen="{ item }">
          <div v-if="item.dokumen">
            <a
              v-for="(dokumen, idx) in item.dokumen"
              :key="idx"
              target="_blank"
              :href="`/api/hr/meet/show_dokumen_ftp?name=${dokumen.nama}`"
              >Dokumen {{ idx + 1
              }}<span v-if="idx != item.dokumen.length - 1">, </span>
            </a>
          </div>
          <span v-else>-</span>
        </template>
      </DataTable>
    </div>
  </div>
</template>
