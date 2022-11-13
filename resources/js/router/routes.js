export default [
    { path: '/', redirect: { name: 'Dashboard' } },
    {
        path: "/auth",
        component: () => import(/* webpackPrefetch: true */ "@/app/Auth"),
        children: [
            { path: '/', redirect: { name: 'Dashboard' } },
            {
                path: "dashboard",
                name: "Dashboard",
                component: () => import(/* webpackPrefetch: true */ "@/pages/dashboard"),
            },
            {
                path: "requests",
                name: "Requests",
                component: () => import(/* webpackPrefetch: true */ "@/pages/requests"),
            },
            {
                path: "issuances",
                name: "Issuances",
                component: () => import(/* webpackPrefetch: true */ "@/pages/issuances"),
            },
            {
                path: "returns",
                name: "Condemns & Returns",
                component: () => import(/* webpackPrefetch: true */ "@/pages/returns"),
            },
            {
                path: "issuances/items",
                name: "Issuance Items",
                component: () => import(/* webpackPrefetch: true */ "@/pages/issuance_items"),
            },
            {
                path: "locations",
                name: "Wards & Offices",
                component: () => import(/* webpackPrefetch: true */ "@/pages/locations"),
            },
            {
                path: "materials",
                name: "Materials",
                component: () => import(/* webpackPrefetch: true */ "@/pages/materials"),
            },
            {
                path: "products",
                name: "Products",
                component: () => import(/* webpackPrefetch: true */ "@/pages/products"),
            },
            {
                path: "storages",
                name: "Storage Management",
                component: () => import(/* webpackPrefetch: true */ "@/pages/storages"),
            },
            {
                path: "reports",
                name: "Monthly Reports",
                component: () => import(/* webpackPrefetch: true */ "@/pages/reports"),
            },
            {
                path: "reports/view",
                name: "View Report",
                component: () => import(/* webpackPrefetch: true */ "@/pages/reports/Report"),
            },
        ]
    }
]