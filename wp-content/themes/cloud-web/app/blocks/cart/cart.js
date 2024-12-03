import axios from 'axios';

window.coreTheme.blocks('cart', (el) => {
    const qtyInput = el.querySelector('.js-qty');
    const qtyBtn = el.querySelectorAll('.js-qty-btn');

    qtyInput.addEventListener('change', ({ target }) => {

        let qty = parseInt(target.value);
        const itemKey = target.dataset.key;
        // const nonce = target.dataset.nonce;
        const nonce = el.querySelector('#woocommerce-cart-nonce').value;

        const cartData = { cart: { [itemKey]: qty } };
        console.log('Value changed', qty, itemKey, nonce, cartData);

        axios.post('/?wc-ajax=update_cart', cartData, {
            headers: {
                'X-WP-Nonce': nonce // Pass WooCommerce nonce
            }
        })
            .then(response => {
                try {
                    // Attempt to parse as JSON
                    const data = response.data;
                    if (data && data.fragments) {
                        console.log('Cart updated successfully:', data);
                        location.reload(); // Reload to reflect changes
                    } else {
                        console.error('Unexpected response:', response);
                    }
                } catch (error) {
                    console.error('Error parsing response:', error);
                    console.error('Raw response:', response);
                }
            })
            .catch(error => {
                console.error('AJAX error:', error);
            });
        // Send the AJAX request
        // fetch('/?wc-ajax=update_cart', {
        //     method: 'POST',
        //     headers: {
        //         'Content-Type': 'application/json',
        //         'X-WP-Nonce': nonce, // Use WooCommerce nonce for security
        //     },
        //     body: JSON.stringify(cartData),
        // })
        //     .then((response) => {
        //         if (!response.ok) {
        //             throw new Error(`HTTP error! status: ${response.status}`);
        //         }
        //         return response.json(); // Attempt to parse JSON
        //     })
        //     .then((data) => {
        //         console.log('Cart update successful:', data);
        //     })
        //     .catch((error) => {
        //         console.error('AJAX error:', error);
        //     });
    });

    qtyBtn.forEach(btn => {
        btn.addEventListener('click', () => {
            let qty = parseInt(qtyInput.value);
            let min = parseInt(qtyInput.min);
            let max = parseInt(qtyInput.max);
            let type = btn.dataset.type;

            let currVal = (type == 'inc') ? qty + 1 : qty - 1;

            qtyInput.value = (currVal <= max && currVal >= min) ? currVal : qty;

            qtyInput.dispatchEvent(new Event('change', { bubbles: true }));
        });
    });
});