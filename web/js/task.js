addEventListener("DOMContentLoaded", (event) => {

    const starsField = document.querySelector('.field-opinions-rate');
    if (starsField) {
        const stars = document.querySelectorAll('.form-opinion .stars-rating span');
        const inputStars = starsField.querySelector('input');
        if (stars.length) {
            stars.forEach(star => {
                star.addEventListener('click', () => {
                    let rate = star.getAttribute('data-val');
                    inputStars.value = rate;
                    // Сбросим все классы fill-star перед установкой новых
                    stars.forEach((s, i) => {
                        if (i < rate) {
                            s.classList.add('fill-star');
                        } else {
                            s.classList.remove('fill-star');
                        }
                    });
                });
            });
        }
    }

});