import { AxiosError } from 'axios';
import { type ApiResponse } from '../types/ApiResponse';
import Swal from 'sweetalert2';

export function ApiErrorHandler<T>(error: AxiosError): void {
    if (error.response) {
        const { status } = error.response;
        const data = error.response.data as ApiResponse<T>;
        switch (status) {
            case 401:
                Swal.fire(data?.message || 'Unauthorized', '', 'error');
                break;
            case 404:
                Swal.fire(data?.message || 'Not found', '', 'error');
                break;
            case 422:
                Swal.fire(data?.message || 'Validation error', '', 'error');
                break;
            case 500:
                Swal.fire(data?.message || 'Internal server error', '', 'error');
                break;
            default:
                Swal.fire(data?.message || 'An error occurred', '', 'error');
                break;
        }
    } else {
        Swal.fire('An error occurred', '', 'error');
    }
}