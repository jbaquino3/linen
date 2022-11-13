import {_get, _post, _put, _delete} from './commons'

const resource = "auth"

export const index = async () => {
    return await _get(`/api/${resource}`)
}

export const updateLocation = async (location_id) => {
    return await _put(`/api/${resource}/location/${location_id}`)
}