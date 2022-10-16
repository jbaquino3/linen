import Swal from 'sweetalert2'

export default function useAlerts(){
    function alert (opts) {
        Swal.fire(opts)
    }

    function alertSuccess({title = "Success!", text = "That's all done!", timer = 1000, showConfirmationButton = false} = {}) {
        alert({
            title: title,
            text: text,
            timer: timer,
            showConfirmButton: showConfirmationButton,
            icon: 'success',
            background: JSON.parse(localStorage.getItem('dark')) ? '#212121' : '#e8e8e8'
        })
    }

    function alertError({title = "Error!", text = "Oops...Something went wrong"} = {}) {
        alert({
            title: title,
            text: text,
            icon: 'error',
            background: JSON.parse(localStorage.getItem('dark')) ? '#212121' : '#e8e8e8'
        })
    }

    function alertWarning({title = "Warning!", text = "I hope you know what you're doing."} = {}) {
        alert({
            title: title,
            text: text,
            icon: 'warning',
            background: JSON.parse(localStorage.getItem('dark')) ? '#212121' : '#e8e8e8'
        })
    }

    function alertInfo({title = "Info", text = "This is an info."} = {}) {
        alert({
            title: title,
            text: text,
            icon: 'info',
            background: JSON.parse(localStorage.getItem('dark')) ? '#212121' : '#e8e8e8'
        })
    }

    function alertConfirmation(options) {
        return new Promise((resolve, reject) => {
            options = Object.assign({
                title: "Are you sure?",
                text: "Are you sure you want to do that?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                background: JSON.parse(localStorage.getItem('dark')) ? '#212121' : '#e8e8e8'
            }, options)
    
            resolve(Swal.fire(options))
        })
    }

    function showInput(options) {
        return new Promise((resolve, reject) => {
            options = Object.assign({
                input: 'textarea',
                inputLabel: 'Message',
                inputPlaceholder: 'Type your message here...',
                inputAttributes: {
                    'aria-label': 'Type your message here'
                },
                showCancelButton: true,
                background: JSON.parse(localStorage.getItem('dark')) ? '#212121' : '#e8e8e8'
            }, options)
    
            resolve(Swal.fire(options))
        })
    }

    function toggleLoading(bool, text = "Loading...") {
        if(bool) {
            alert({
                html: '<h1>' + text + '</h1>',
                showConfirmButton: false,
                allowOutsideClick: false,
                background: JSON.parse(localStorage.getItem('dark')) ? '#212121' : '#e8e8e8'
            })
        } else {
            Swal.close()
        }
    }

    return {
        alertSuccess,
        alertError,
        alertWarning,
        alertInfo,
        alertConfirmation,
        showInput,
        toggleLoading
    }
}