(function () {
  function convertPhones() {
    // DETAIL VIEW
    document
      .querySelectorAll('span.value[data-field-type="phone"]')
      .forEach(function (span) {
        if (!span || span.querySelector("a")) return;

        let number = span.textContent || "";
        number = number
          .replace(/\s+/g, " ")
          .replace(/\u00A0/g, " ")
          .trim();

        if (number.length < 5) return; // još se možda učitava

        const clean = number.replace(/[^\d+]/g, "");
        if (clean.length > 4) {
          span.innerHTML = `<a href="tel:${clean}">${number}</a>`;
        }
      });

    // LIST VIEW
    document
      .querySelectorAll(
        'td.listViewEntryValue[data-field-type="phone"] span.value'
      )
      .forEach(function (valueSpan) {
        if (!valueSpan || valueSpan.querySelector("a")) return;

        let number = valueSpan.textContent || "";
        number = number
          .replace(/\s+/g, " ")
          .replace(/\u00A0/g, " ")
          .trim();

        if (number.length < 5) return;

        const clean = number.replace(/[^\d+]/g, "");
        if (clean.length > 4) {
          valueSpan.innerHTML = `<a href="tel:${clean}">${number}</a>`;
        }
      });
  }

  // Pokreni odmah
  convertPhones();

  // Ponovi svakih 2 sekunde – radi i kod paginacije i dinamičkog učitavanja
  setInterval(convertPhones, 2000);
})();
