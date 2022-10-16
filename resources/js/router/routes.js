export default [
    { path: '/', redirect: { name: 'Auth' } },
    {
        path: "/auth",
        name: "Auth",
        component: () => import(/* webpackPrefetch: true */ "@/app/Auth"),
    }
]