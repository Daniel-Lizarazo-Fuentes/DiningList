if (document.getElementById("categoryNav") != null) {
    const collection = document.querySelectorAll('[id^="button"]');

    for (let i = 0; i < collection.length; i++) {
        let button = collection.item(i);

        button.addEventListener("click", function () {
            const displayCollection = document.querySelectorAll('[id^="display"]');

            const categoryId = collection.item(i).id.replace('button', 'display');

            for (let k = 0; k < displayCollection.length; k++) {
                let category = displayCollection.item(k);

                if (category.id != categoryId) {
                    category.classList.add("hidden");
                } else {

                    category.classList.remove("hidden");
                }
            }

        });
    }
}