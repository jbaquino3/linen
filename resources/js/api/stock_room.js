import {_get, _post, _put, _delete} from './commons'

const resource = "stock_room"

export const index = async () => {
    return await _get(`/api/${resource}`)
}

export const read = async (id) => {
    return await _get(`/api/${resource}/${id}`)
}

export const store = async (data) => {
    return await _post(`/api/${resource}`, data)
}

export const update = async (data, id) => {
    return await _put(`/api/${resource}/${id}`, data)
}

export const destroy = async (id) => {
    return await _delete(`/api/${resource}/${id}`)
}