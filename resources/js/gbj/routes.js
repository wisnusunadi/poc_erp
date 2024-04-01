import VueRouter from "vue-router";
import PermintaanReworkGBJ from "./page/PermintaanReworkGBJ";
import penerimaanRework from "./page/penerimaanRework";
import penggantianRework from "./page/penggantianRework";

const routes = [
    {
        path: "/gbj/rework/permintaan-rework",
        component: PermintaanReworkGBJ,
    },
    {
        path: "/gbj/rework/penerimaan-rework",
        component: penerimaanRework,
    },
    {
        path: "/gbj/rework/penggantian-rework",
        component: penggantianRework,
    },
    {
        path: "/gbj/bso",
        component: () => import("./page/transferProduk"),
    },
    {
        path: "/gbj/so",
        component: () => import("./page/permintaanSO"),
    },
    {
        path: "/gbj/dp",
        component: () => import("./page/penerimaanFinishGoods"),
    },
    {
        path: "/gbj/nso",
        component: () => import("./page/permintaanNSO"),
    },
    {
        path: "/gbj/nso/detail/:id",
        name: "detailNSO",
        component: () => import("./page/permintaanNSO/selesaiProses/detail"),
    }
];

const router = new VueRouter({
    mode: "history",
    routes,
});

export default router;
