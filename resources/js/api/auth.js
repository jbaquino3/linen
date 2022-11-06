import {_get, _post, _put, _delete} from './commons'

const resource = "auth"

export const index = async () => {
    return await _get(`/api/${resource}`)
}