
# Fakultet elektrotehnike, računarstva i informacijskih tehnologija Osijek  
## Napredno Web programiranje  

### Laboratorijske vježbe – vježba 6  

**Node.js**, Express i MongoDB  

---

## Zadatak 1

U prilogu vježbe dodana su dva primjera aplikacija koji koriste Node.js, Express i MongoDB.  
Primjer `lv6-crud` omogućuje CRUD operacije sa podacima.  
Drugi primjer je RESTful aplikacija koja upotrebom jQuery omogućuje dodavanje, pregled i brisanje korisnika.  
Potrebno je proučiti obje aplikacije.  
Unutar oba direktorija je `Readme.md` sa uputama kako se instaliraju i pokreću aplikacije.  

1. Ako na računalu nije instaliran Express potrebno ga je instalirati sa:

   ```bash
   npm install -g express
   npm install -g express-generator
```

2. Napravite novu Express instalaciju `projects` u kojoj će korisnici voditi evidenciju svojih projekata.
Projekt treba imati atribute:
    - naziv projekta
    - opis projekta
    - cijena projekta
    - obavljeni poslovi
    - datum početka
    - datum završetka

Nova instalacija se stvara naredbom:

```bash
express projects
```

3. Omogućiti CRUD operacije sa projektima, to jest:
    - dodavanje novog projekta
    - uređivanje projekta
    - brisanje projekta
    - pregled detalja o projektu
4. Na svaki projekt se može dodati više članova projektnog tima koji će raditi na projektu
(članovi tima dodaju se upisom preko forme).
5. Nakon završetka aplikacije potrebno ju je spremiti jer se iduća vježba nastavlja na ovu vježbu.
```
<span style="display:none">[^1]</span>

<div align="center">⁂</div>

[^1]: LV6.pdf```

