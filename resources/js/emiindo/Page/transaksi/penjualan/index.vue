<script>
import detailComponents from '../detail/index.vue'
import pagination from '../../../components/pagination.vue'
import statusComponents from '../../../components/status.vue'
import persentase from "../../../components/persentase.vue";
export default {
    components: {
        detailComponents,
        pagination,
        statusComponents,
        persentase,
    },
    props: ['penjualan'],
    data() {
        return {
            search: '',
            showModal: false,
            detailSelected: {},
            status: [
                {
                    text: 'Penjualan',
                    value: 7
                },
                {
                    text: 'PO',
                    value: 9
                },
                {
                    text: 'Gudang',
                    value: 6
                },
                {
                    text: 'QC',
                    value: 8
                },
                {
                    text: 'Kirim',
                    value: 11
                },
            ],
            jenisTransaksi: [
                {
                    value: 'ekatalog',
                    text: 'E-Catalogue'
                },
                {
                    value: 'spa',
                    text: 'SPA'
                },
                {
                    value: 'spb',
                    text: 'SPB'
                }
            ],
            renderPaginate: []
        }
    },
    methods: {
        calculateDateFromNow(date) {
            // kalkulasi tanggal dari sekarang
            const tglSekarang = new Date();
            const tglKontrak = new Date(date);
            if (tglKontrak < tglSekarang) {
                return {
                    text: `Lebih ${moment(tglSekarang).diff(tglKontrak, 'days')} Hari`,
                    color: 'text-danger font-weight-bold',
                    icon: 'fas fa-exclamation-circle'
                }
            } else if (tglKontrak > tglSekarang) {
                return {
                    text: `${moment(tglKontrak).diff(tglSekarang, 'days')} Hari Lagi`,
                    color: 'text-dark',
                    icon: 'fas fa-clock'
                }
            } else {
                return {
                    text: 'Batas Kontrak Habis',
                    color: 'text-danger',
                    icon: 'fas fa-exclamation-circle'
                }
            }
        },
        tambah() {
            window.location.href = '/penjualan/penjualan/create'
        },
        filter(year, status) {
            this.$store.dispatch('setYears', year)
            if (status != '') {
                this.$emit('filter', status)
            } else {
                this.$emit('refresh')
            }
        },
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        cekIsString(value) {
            if (typeof value === 'string') {
                return true
            } else {
                return false
            }
        },
        detail(item) {
            this.detailSelected = item
            this.showModal = true
            this.$nextTick(() => {
                $('.modalDetail').modal('show')
            })
        },
    },
    computed: {
        yearsComputed() {
            let years = []
            for (let i = 0; i < 5; i++) {
                years.push(moment().subtract(i, 'years').format('YYYY'))
            }
            return years
        },
        filteredDalamProses() {
            const includesSearch = (obj, search) => {
                if (obj && typeof obj === 'object') {
                    return Object.keys(obj).some(key => {
                        if (typeof obj[key] === 'object') {
                            return includesSearch(obj[key], search);
                        }
                        return String(obj[key]).toLowerCase().includes(search.toLowerCase());
                    });
                }
                return false;
            };

            return this.penjualan.filter(data => includesSearch(data, this.search));
        },
    }
}
</script>
<template>
    <div class="card">
        <detailComponents v-if="showModal" @close="showModal = false" :detail="detailSelected" />
        <div class="card-body">
            <div class="d-flex bd-highlight">
                <div class="p-2 flex-grow-1 bd-highlight">
                    <span class="filter">
                        <button class="btn btn-outline-secondary btn-sm" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <i class="fas fa-filter"></i> Filter Tahun
                        </button>
                        <button class="btn btn-outline-info btn-sm" @click="tambah">
                            <i class="fas fa-plus"></i> Tambah
                        </button>
                        <form id="filter_ekat">
                            <div class="dropdown-menu" style="">
                                <div class="px-3 py-3">
                                    <div class="form-group">
                                        <div class="form-check" v-for="(year, key) in yearsComputed" :key="key">
                                            <input class="form-check-input form-years-select" type="radio" :value="year"
                                                :id="`status${key}`" @click="filter(year, '')" :checked="key == 0"
                                                v-model="$store.state.years">
                                            <label class="form-check-label" :for="`status${key}`">
                                                {{ year }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="jenis_penjualan">Jenis Penjualan</label>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check" v-for="(status, key) in jenisTransaksi" :key="key">
                                            <input class="form-check-input" type="checkbox" :value="status.value"
                                                :id="`status${key}`" @click="$emit('filterTransaksi', status.value)">
                                            <label class="form-check-label" :for="`status${key}`">
                                                {{ status.text.charAt(0).toUpperCase() + status.text.slice(1) }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="jenis_penjualan">Status</label>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check" v-for="(status, key) in status" :key="key">
                                            <input class="form-check-input" type="checkbox" :value="status.value"
                                                :id="`status${key}`" @click="filter($store.state.years, status.value)">
                                            <label class="form-check-label" :for="`status${key}`">
                                                {{ status.text.charAt(0).toUpperCase() + status.text.slice(1) }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </span>
                </div>
                <div class="p-2 bd-highlight"><input type="text" class="form-control" v-model="search"
                        placeholder="Cari..."></div>
            </div>

            <table class="table text-center" v-if="!$store.state.loading">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor SO</th>
                        <th>Nomor AKN</th>
                        <th>Nomor PO</th>
                        <th>Tanggal PO</th>
                        <th>Tanggal Delivery</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody v-if="renderPaginate.length > 0">
                    <tr v-for="(item, index) in renderPaginate" :key="index"
                        :class="{ 'strike-through-row text-danger font-weight-bold': item.status == 'batal' }">
                        <td :class="{ 'strike-through': item.status == 'batal' }">{{ index + 1 }}</td>
                        <td :class="{ 'strike-through': item.status == 'batal' }">{{ item.so }}</td>
                        <td :class="{ 'strike-through': item.status == 'batal' }">
                            {{ item.no_paket }}
                            <statusComponents :status="item.status" v-if="item?.status" />
                        </td>
                        <td :class="{ 'strike-through': item.status == 'batal' }">{{ item.no_po }}</td>
                        <td :class="{ 'strike-through': item.status == 'batal' }">{{ item.tgl_po }}</td>
                        <td :class="{ 'strike-through': item.status == 'batal' }">
                            <div v-if="item.tgl_kontrak_custom">
                                <div :class="calculateDateFromNow(item.tgl_kontrak_custom).color">{{
            dateFormat(item.tgl_kontrak_custom) }}</div>
                                <small :class="calculateDateFromNow(item.tgl_kontrak_custom).color">
                                    <i :class="calculateDateFromNow(item.tgl_kontrak_custom).icon"></i>
                                    {{ calculateDateFromNow(item.tgl_kontrak_custom).text }}
                                </small>
                            </div>
                            <div v-else></div>
                        </td>
                        <td :class="{ 'strike-through': item.status == 'batal' }">{{ item.nama_customer }}</td>
                        <td>
                            <persentase :persentase="item.persentase" v-if="!cekIsString(item.persentase)" />
                            <span class="red-text badge" v-else>{{ item.persentase }}</span>
                        </td>
                        <td>
                            <button class="btn btn-outline-primary btn-sm" @click="detail(item)">
                                <i class="fas fa-eye"></i>
                                Detail
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div v-else>
                <div class="d-flex justify-content-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>

            <pagination :filteredDalamProses="filteredDalamProses" v-if="!$store.state.loading"
                @updateFilteredDalamProses="updateFilteredDalamProses" />
        </div>
    </div>
</template>