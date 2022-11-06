import { defineStore } from 'pinia'
import { ref } from 'vue'
import { index } from '@/api/auth'

export const useAuthStore = defineStore('auth', () => {
    const user = ref(null)
    const user_loading = ref(true)

    async function fetchUser() {
        user_loading.value = true
        const res = await index()
        if(res.status) {
            user.value = res.data
            localStorage.setItem("employeeid", user.value.employeeid)
        } else {
            // window.location.href = "/auth?employeeid=" + localStorage.getItem("employeeid")
            window.location.href = "http://192.168.6.179"
        }
        user_loading.value = false
    }
  
    return { user, fetchUser }
})