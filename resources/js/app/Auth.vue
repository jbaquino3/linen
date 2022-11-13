<template>
    <div>
        <v-app-bar :dark="darkMode" dense elevation="0" class="no-print">
            <v-avatar size="36" class="mx-3">
                <v-img src="/assets/logo.png"></v-img>
            </v-avatar>

            <v-app-bar-title>
                <div class="headline font-weight-bold">
                    Linen Inventory System
                </div>
            </v-app-bar-title>

            <v-spacer></v-spacer>

            <div class="title mr-2 d-none d-sm-flex">
                {{authStore.user ? (authStore.user.name) : ""}}
                <v-menu offset-y>
                    <template v-slot:activator="{ on, attrs }">
                        <v-icon v-bind="attrs" v-on="on">
                            {{mdiMenuDown}}
                        </v-icon>
                    </template>

                    <v-list>
                        <v-list-item>
                            <v-list-item-title>
                                Change Ward/Unit
                            </v-list-item-title>
                        </v-list-item>
                        <v-list-item link href="http://192.168.6.179">
                            <v-list-item-title>
                                Go to Intranet
                            </v-list-item-title>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-title>
                                Dark Mode
                                <v-chip x-small class="ml-2 white--text" color="red">BETA</v-chip>
                            </v-list-item-title>

                            <v-list-item-action>
                                <v-switch v-model="darkMode"></v-switch>
                            </v-list-item-action>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </div>

            <template v-slot:extension>
                <template v-for="(item, i) in menus">
                    <v-btn link :to="item.route" :key="i" text active-class="primary">
                        {{item.text}}
                    </v-btn>
                </template>
            </template>
        </v-app-bar>

        <v-container fluid>
            <router-view></router-view>
        </v-container>
    </div>
</template>

<script>
    import { useAuthStore } from '@/stores/auth'
    import { ref, computed } from 'vue'
    import { useVuetify } from '@/plugins/UseVuetify'
    import { mdiMenuDown } from '@mdi/js'

    const authStore = useAuthStore()

    export default {
        setup() {
            const vuetify = useVuetify()
            const drawer = ref(true)
            const selected_menu = ref(0)

            const darkMode = computed({
                get() {
                    return vuetify.theme.dark
                },
                set(v) {
                    localStorage.setItem('dark', JSON.stringify(v))
                    vuetify.theme.dark = JSON.parse(localStorage.getItem('dark'))
                },
            })

            return {
                authStore, drawer, selected_menu,
                darkMode, vuetify, menus, ...icons
            }
        },
    }

    const icons = {
        mdiMenuDown
    }

    const menus = computed(() => {
        if(authStore.user && (authStore.user?.role == "ADMIN" || authStore.user?.role == "SUPER_ADMIN")) {
            return [
                {icon: "dashboard", text: "Home", route: "/auth/dashboard", access: true},
                {icon: "location", text: "Ward & Offices", route: "/auth/locations", access: true},
                {icon: "material", text: "Materials", route: "/auth/materials", access: true},
                {icon: "product", text: "Products", route: "/auth/products", access: true},
                {icon: "storage", text: "Storage Management", route: "/auth/storages", access: true},
                {icon: "transaction", text: "Transactions", route: "/auth/issuances", access: true},
                {icon: "request", text: "Requests", route: "/auth/requests", access: true},
                {icon: "report", text: "Monthly Reports", route: "/auth/reports", access: true}
            ]
        } else {
            return [
                {icon: "dashboard", text: "Dashboard", route: "/auth/dashboard", access: true},
                {icon: "request", text: "Requests", route: "/auth/requests", access: true},
                {icon: "report", text: "Monthly Reports", route: "/auth/reports", access: true}
            ]
        }
    })
</script>