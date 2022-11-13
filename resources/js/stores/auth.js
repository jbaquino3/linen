import { defineStore } from 'pinia'
import { ref } from 'vue'
import { index, updateLocation } from '@/api/auth'

export const useAuthStore = defineStore('auth', () => {
    const user = ref(null)
    const user_loading = ref(true)
    const ward_dialog = ref(false)

    async function fetchUser() {
        user_loading.value = true
        const res = await index()
        if(res.status) {
            user.value = res.data
            localStorage.setItem("employeeid", user.value.employeeid)

            if(!user.value.location_id) {
                ward_dialog.value = true
            }
        } else {
            // window.location.href = "/auth?employeeid=" + localStorage.getItem("employeeid")
            window.location.href = "http://192.168.6.179"
        }
        user_loading.value = false
    }

    async function updateUserLocation(location_id) {
        const res = await updateLocation(location_id)
        return res
    }
  
    return { user, ward_dialog, fetchUser, updateUserLocation }
})