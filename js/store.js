function store_builder() {
    this.state = {};
    this.put = function(k, v) {
        if (k != undefined && v != undefined) {
            (this.state)[k] = v;
        }
    }
}