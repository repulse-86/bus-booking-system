import Swal from 'sweetalert2'
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';

const $toast = useToast();

export const formatDate = (date) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(date).toLocaleDateString('en-US', options);
};

export function formatNumber(num) {
    if (num >= 1e9) {
        return (num / 1e9).toFixed(1) + "B"; // Billions
    } else if (num >= 1e6) {
        return (num / 1e6).toFixed(1) + "M"; // Millions
    } else if (num >= 1e3) {
        return (num / 1e3).toFixed(1) + "K"; // Thousands
    }
    return num.toString();
}

export const showConfirmation = ({ title, text, confirmButtonText, cancelButtonText }) => {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: confirmButtonText || 'Yes, proceed!',
        cancelButtonText: cancelButtonText || 'No, cancel!',
        customClass: {
            confirmButton: 'my-primary-btn-bg-color',
            cancelButton: 'my-secondary-btn-bg-color',
        }
    });
};

export const showAlert = ({ icon, title, text = null }) => {
    Swal.fire({
        icon: icon,
        title: title,
        text: text,
        showConfirmButton: false,
        timer: 1000,
    });
};

export function toast(message) {
    $toast.success(message);
}

export const showInputAlert = ({ title, text, placeholder, confirmButtonText, cancelButtonText }) => {
    return Swal.fire({
        title: title,
        text: text,
        input: 'textarea',
        inputPlaceholder: placeholder || 'Enter your text here...',
        showCancelButton: true,
        confirmButtonText: confirmButtonText || 'Submit',
        cancelButtonText: cancelButtonText || 'Cancel',
        customClass: {
            confirmButton: 'my-primary-btn-bg-color',
            cancelButton: 'my-secondary-btn-bg-color',
        },
        preConfirm: (inputValue) => {
            if (!inputValue) {
                Swal.showValidationMessage('Please enter some text');
                return false;
            }
            return inputValue;
        }
    });
};
