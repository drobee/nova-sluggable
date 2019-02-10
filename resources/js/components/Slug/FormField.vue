<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <input
                :id="field.name"
                type="text"
                class="w-full form-control form-input form-input-bordered"
                :class="errorClasses"
                :placeholder="field.name"
                v-model="value"
            />

            <p v-if="hasError" class="my-2 text-danger">
                {{ firstError }}
            </p>
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    /**
     * Mount the component.
     */
    mounted() {
        Nova.$on('field-update-' + this.field.name, ({value}) => {
            this.generateSlug(value)
        })
    },

    methods: {
        /*
         * Generate the slug
         */
        generateSlug(value) {
            const options = {
                model: this.field.model || null,
                options: this.field.options || null,
                attribute: this.field.attribute || null,
                value,
            }
            Nova.request().post('/nova-vendor/nova-sluggable/generate', options)
                .then(response => {
                    this.value = response.data.slug
                })
                .catch(error => {
                    const errorData = error.response.data.error || null;
                    if (errorData) {
                        this.hasError = true
                        this.firstError = errorData
                    }
                })
        },
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = this.field.value || ''
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            formData.append(this.field.attribute, this.value || '')
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            this.value = value
        },
    },
}
</script>
