<script setup>
/**
 * Props:
 *  - icon        (classe Bootstrap-Icons, ex.: "bi-book")
 *  - iconColor   (classe utilitária de cor, ex.: "text-warning")
 *  - title       (texto superior)
 *  - count       (número grande à direita)
 *  - subtitle    (texto miúdo embaixo)
 */
const props = defineProps({
    icon: { type: String, required: true },
    iconColor: { type: String, default: 'text-primary' },
    title: { type: String, required: true },
    count: { type: [Number, String], required: true },
    subtitle: { type: String, default: '' }
});
</script>

<template>
    <div class="card shadow-sm h-100">
        <div class="card-body d-flex flex-column justify-content-between p-3">
            <div class="d-flex align-items-start">
                <i :class="['bi', icon, iconColor, 'fs-2 me-3']"></i>

                <div class="flex-grow-1">
                    <div class="fw-semibold small text-secondary text-uppercase">
                        {{ title }}
                    </div>
                    <div class="fs-4 fw-bold">
                        {{ count }}
                    </div>
                </div>
            </div>

            <div v-if="subtitle" class="border-top pt-2 mt-3 small text-muted">
                <i class="bi bi-info-circle-fill me-1"></i> {{ subtitle }}
            </div>
            <slot></slot>
        </div>
    </div>
</template>
