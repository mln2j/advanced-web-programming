var mongoose = require('mongoose');
var projectSchema = new mongoose.Schema({
    naziv: String,
    opis: String,
    cijena: Number,
    obavljeni_poslovi: String,
    datum_pocetka: { type: Date, default: Date.now },
    datum_zavrsetka: Date,
    voditelj: { type: mongoose.Schema.Types.ObjectId, ref: 'User' },
    clanovi_tima: [{ type: mongoose.Schema.Types.ObjectId, ref: 'User' }],
    arhiviran: { type: Boolean, default: false }
});
mongoose.model('Project', projectSchema);
