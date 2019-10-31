Nova.booting((Vue, router, store) => {
    router.addRoutes([
        {
            name: 'price-tracker',
            path: '/price-tracker',
            component: require('./components/Tool'),
        },
    ])
})
