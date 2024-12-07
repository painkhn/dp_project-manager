// resources/js/directives/dateMask.ts

import { DirectiveBinding } from 'vue';

export const dateMask = {
    mounted(el: HTMLInputElement, binding: DirectiveBinding) {
        el.addEventListener('input', function (event) {
            const input = event.target as HTMLInputElement;
            let value = input.value.replace(/\D/g, ''); // Удаляем все нецифровые символы
            if (value.length > 8) {
                value = value.slice(0, 8); // Ограничиваем длину до 8 символов
            }
            if (value.length > 4) {
                value = value.slice(0, 4) + '-' + value.slice(4, 6) + '-' + value.slice(6); // Добавляем разделители '-'
            } else if (value.length > 2) {
                value = value.slice(0, 4) + '-' + value.slice(4); // Добавляем разделитель '-'
            }
            input.value = value;
        });
    },
};