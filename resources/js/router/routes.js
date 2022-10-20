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
                path: "transactions",
                name: "Transactions",
                component: () => import(/* webpackPrefetch: true */ "@/pages/transactions"),
            },
            {
                path: "transactions/items",
                name: "Transactions Items",
                component: () => import(/* webpackPrefetch: true */ "@/pages/transaction_items"),
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
        ]
    }
]