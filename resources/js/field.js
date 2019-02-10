Nova.booting((Vue, router, store) => {
    Vue.component('index-nova-sluggable-slug-field', require('./components/Slug/IndexField'))
    Vue.component('detail-nova-sluggable-slug-field', require('./components/Slug/DetailField'))
    Vue.component('form-nova-sluggable-slug-field', require('./components/Slug/FormField'))

    Vue.component('index-nova-sluggable-sluggabletext-field', require('./components/SluggableText/IndexField'))
    Vue.component('detail-nova-sluggable-sluggabletext-field', require('./components/SluggableText/DetailField'))
    Vue.component('form-nova-sluggable-sluggabletext-field', require('./components/SluggableText/FormField'))
})
