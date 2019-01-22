# Projekt Wulkanizator

**Autorzy**: Rafał Gałek, Dawid Jasztal


## Cel i przeznaczenie dokumentu

Niniejszy dokument ma na celu przedstawienie specyfikacji projektu system informatycznego bloga. Dokument uwzględnia pełną funkcjonalność systemu, zawiera szczegółowy spis wymagań funkcjonalnych i niefunkcjonalnych, przypadki użycia oraz listę aktorów. Ponadto w kolejnych wersjach dokumentu, został on rozbudowany o przykładowy diagram BPMN, projekt bazy danych oraz wykres Gantta.

## **Słownik użytych skrótów**

|  Skrót/pojęcie |Opis                           | 
|----------------|-------------------------------|
| W              | Wymaganie                     |
| UC             | Use Case - przypadek użycia   |


## Ogólny opis założeń systemu i wymagań

Ogólnym założeniem projektu jest budowa systemu zarządzania blogiem. Strona, jak i system, oferuje rejestracje i logowanie użytkowników. Każdy z użytkowników może przeglądać artykułu oraz ciekawostki. Po zalogowaniu mają dostęp do komentowania artykułów, jak i zarządzania własnym koontem i komentarzami. Administrator ma możliwość dodania artykułu, tagów oraz ciekawostki. Dodatkowo może nimi zarządzać, edytować je. Strona oferuje również szybki kontakt z administratorem dla wszystkich użytkowników, poprzez prosty formularz na stronie.

## Lista wymagań

|  ID |NAZWA            |OPIS                         |          UC   |
|-----|-----------------|-----------------------------|---------------|
|W01  |REJESTRACJA      |Użytkownik może zarejestrować się poprzez formularz rejestracji bez akceptacji Administratora.| UC02|
|W02  |AUTORYZACJA      |Użytkownik może zalogować się poprzez formularz. |UC01|
|W03  |WYLOGOWANIE      |Użytkownik może wylogować się poprzez przycisk.| UC03|
|W04  |PRZEGLĄD ARTYKUŁÓW|Użytkownik może przeglądać artykuły.| UC04|
|W05  |PRZEGLĄD CIEKAWOSTEK|Użytkownik może przeglądać ciekawostki.| UC05|
|W06  |ZARZĄDZANIE KONTAMI|Administrator ma możliwość zarządzaniania kontami użytkowników. Każdy użytkownik może zarządzać swoimi danymi.| UC17|
|W07  |FORMULARZ KONTAKTOWY|Użytkownik może wysłać wiadomość kontaktową w celach informacyjnych lub reklamacji.| UC18|
|W08  |ZARZĄDZANIE ARTYKUŁAMI|Administrator ma możliwość zarządzania artykułami.| UC06, UC07, UC08, UC09|
|W09  |ZARZĄDZANIE CIEKAWOSTKAMI|Administrator ma możliwość zarządzania ciekawostkami.| UC10, UC11, UC12|
|W10  |ZARZĄDZANIE TAGAMI|Administrator ma możliwość zarządzania tagami.| UC13, UC14|
|W11  |KOMENTOWANIE ARTYKUŁÓW|Zalogowani użytkownicy mają możliwość komentowania artykułów.| UC15|
|W12  |ZARZĄDZANIE KOMENTARZAMI|Administrator ma możliwość zarządzania komentarzami. Każdy użytkownik może zarządzać swoimi komentarzami.| UC16|


## Określenie celu

Cele projektu - zgodnie z metodologią S.M.A.R.T. (pol. Z.M.O.R.A. - Zdefiniowane, Mierzalne, Ograniczone w czasie, Realne, Akceptowalne)

a) Cele ogólne (np. bycie liderem na rynku, odzyskanie pozycji itp.)

-   zbudowanie działającego systemu bloga,
    
-   zbudowanie innowacyjnego projektu cieszącego się popularnością.
        

b) Cele bezpośrednie (np. zarobienie określonej sumy, zdobycie X klientów itp.)

-   zdobycie 5% klientów korzystających z podobnych rozwiązań w okresie 1 miesiąca od wdrożenia,
    
-   zwrot budżetu początkowego w okresie 12 miesięcy od wdrożenia,
    
-   znaleźć się na wysokim miejscu w pozycjonowaniu Google.
    

c) Cele produktowe (np. aplikacja do... , wdrożenie w ciągu X miesięcy, akcja marketingowa itp.)

-   wdrożenie systemu w 7 miesięcy,
    
-   wprowadzenie responsywności na urządzenia mobilne,
    
-   przeniesienie całej papierowej bazy na bazę danych,
    
-   kampania reklamowa na środkach publicznego transportu oraz reklamy internetowe.
    

d) Cele proceduralne - określenie co będzie potrzebne(budżet, czas, pracownicy, forma realizacji)

-   przewidywany budżet 10 000 zł,
    
-   przewidywany czas to 7 miesięcy,
    
-   3 pracowników zajmujących się programowaniem,
    
-   realizacja projektu w HTML, JS, CSS, PHP.


## Określenie kto będzie korzystał z systemu

Spis rodzajów użytkowników wraz z ich celami i funkcjami.
| TYP  | CELE  |  FUNKCJE   | 
|------|-------|------------|  
 | UŻYTKOWNICY   | W01, W02, W03, W05, W09  | Użytkownicy portalu pełnią funkcję czytelników bloga. Mają możliwość kontaktowania się z administratorami poprzez formularz. Jako zalogowany użytkownik otrzymuje możliwość komentowania artykułów oraz zarządzania własnym kontem. |  
 | ADMINISTRATORZY | W04, W06, W07, W08, W10 | Administrator posiada możliwość zarządzania artykułami, ciekawostkami oraz tagami. Może je dodawać, edytować, publikować/odpublikować, oraz usuwać. Jako administrator ma wgląd w komentarze, również te usunięte. Dodatkowo może zarządzać profilami użytkowników | 




## Lista przypadków użycia

Priorytet uprawnień:  
Użytkownik Niezalogowany (UN) -> Użytkownik (U) -> Administrator (A)

|ID   | NAZWA           |  UWAGI                      |     OPIS                    |
|-----|-----------------|-----------------------------|-----------------------------|
|UC01 |Autoryzacja | UN| Użytkownik podejmuje próbę autoryzacji, wprowadzając w formularzu logowania swój adres e-mail oraz hasło. Sprawdzana jest obecność podanego adresu e-mail w bazie danych użytkowników oraz poprawność wprowadzonego hasła. Jeśli hasło jest niepoprawne, wyświetlany jest odpowiedni komunikat oraz link do zresetowania hasła. Po poprawnym wprowadzeniu hasła użytkownik zostaje zalogowany i uzyskuje dostęp do funkcjonalności użytkownika zalogowanego|
|UC02 |Rejestracja |UN |Użytkownik podczas próby rejestracji podaje w formularzu swój adres e-mail (sprawdzana jest jego poprawność – jeśli niepoprawny, wyświetlany jest popup proszący o poprawę) i hasło oraz powtórzone hasło. Asynchronicznie sprawdzana jest zgodność obu pól – jeśli się nie zgadzają, użytkownik jest proszony o poprawienie zgodności. Adresy e-mail w bazie danych nie mogą się powtarzać.|
|UC03 |Wylogowanie |U |Zalogowany użytkownik może wylogować się poprzez naciśnięcie przycisku na dowolnej stronie. Wówczas tracony jest dostęp do funkcjonalności zalogowanego użytkownika.|
|UC04 | Przegląd artykułów |UN | Każdy z użytkowników po przejściu do zakładki "Artykuły" ma możliwość przeglądania dodanych artykułów. Jeśli do artykułu są komentarze, również są widoczne.|
|UC05 | Przegląd ciekawostek|UN|  Każdy z użytkowników po przejściu do zakładki "Artykuły" ma możliwość przeglądania dodanych ciekawostek, mieszczących się obok artykułów.|
|UC06| Dodawanie artykułu| A | W celu dodania artykułu, Administrator uzupełnia odpowiedni formularz. W formularzu uzupełnia pola takie jak tytuł artykułu, zawartość i data publikacji. Po zaakceptowaniu formularza, artykuł trafia na podstronę "artykuły".|
|UC07| Zarządzanie statusem artykułu|A|Administrator decyduje o tym czy artykuł jest opublikowany. Za pomocą odpowiedniego przycisku może odpublikować artykuł lub opublikować odpublikowany artykuł.|
|UC08| Usuwanie artykułu|A| Administrator może usuwać artykuły poprzed odpowiedni przycisk w sekcji zarządzania artykułami.|
|UC09|Edytowanie artykułu|A|Administrator może edytować artykuł, modyfikuje formularz, który uzupełniał podczas dodawania artykułu. Zmodyfikowane dane zatwierdza przyciskiem.|
|UC10| Dodawanie ciekawostki| A | W celu dodania ciekawostki, Administrator uzupełnia odpowiedni formularz. W formularzu uzupełnia pola takie jak tytuł ciekawostki, krótki opis i link do zawartości. Po zaakceptowaniu formularza, ciekawostka trafia w odpowiednie miejsce na podstronę "artykuły".|
|UC11| Usuwanie ciekawostki|A| Administrator może usuwać ciekawostki poprzed odpowiedni przycisk w sekcji zarządzania ciekawostkami.|
|UC12|Edytowanie ciekawostki|A|Administrator może edytować ciekawostkę, modyfikuje formularz, który uzupełniał podczas dodawania ciekawostki. Zmodyfikowane dane zatwierdza przyciskiem.|
|UC13| Dodawanie tagu| A | W celu dodania tagów, Administrator uzupełnia odpowiedni formularz. W formularzu uzupełnia pole z nazwą tagu oraz wybiera artykuły, do których chce je dodać. Po zaakceptowaniu formularza, tag jej dodawany do wybranych artykułów.|
|UC14| Usuwanie tagu|A| Administrator może usuwać tagi poprzed odpowiedni przycisk w sekcji zarządzania tagami.|
|UC15| Dodawanie komentarza| U | Zalogowany użytkownik pod dowolnym artykułem może dodać komentarz.|
|UC16| Usuwanie komentarza| U | W sekcji komentarzy w profilu użytkownik może usunąć swój komentarz.|
|UC17| Edytowanie danych| U | Zalogowany użytkownik w swoim profilu ma możliwość zmiany swoich danych, takich jak e-mail, imię, nazwisko czy avatar.|
|UC18| Wysyłanie wiadomości kontaktowej| UN | Użytkownik może skorzystać z zakładki "Kontakt" w celu skontaktowania się z Administracją. |


## Lista aktorów

|TYP  | CELE            |  FUNKCJE                    |   RODZAJ                |
|-----|-----------------|-----------------------------|-------------------------|
|UŻYTKOWNICY NIEZALOGOWANI|W01, W02, W04, W05, W07|Użytkownik niezalogowany może utworzyć konto, zalogować się, przeglądać artykuły i ciekawostki oraz uzupełnić formularz kontaktowy| Osoba|
|UŻYTKOWNICY|W03, W06, W11, W12|Użytkownik zalogowany może zarządzać kontem, przeglądać artykuły i ciekawostki, komentować, zarządzać komentarzami oraz wylogować się | Osoba|
|ADMINISTRATORZY|W06, W08-W12|Administrator może dodawać artykuły, ciekawostki, tagi oraz komentarze oraz nimi zarządzać. Dodatkowo ma możliwość zarządzania kontami użytkowników. Ponadto zarządza wiadomościami kontaktowymi. | Osoba|

## Diagram UC
![Diagram UC](https://pics.tinypic.pl/i/00978/ejxf10p53ptj.png)
## Przypadki użycia

### UC01 Autoryzacja

Jako użytkownik niezalogowany,

chcę zalogować się do serwisu,

aby korzystać z funkcjonalności dostępnej tylko dla osób zalogowanych. 

Scenariusz:

1.  Użytkownik niezalogowany przechodzi do formularza logowania.
    
2.  Podaje w pola swój login (mail) i hasło.
    
3.  System przetwarza zapytanie w celu sprawdzenia poprawności sprawdzonych danych.
    
4.  W przypadku wprowadzenia poprawnych danych tworzona jest sesja, a użytkownik przenoszony na poprzednio przeglądaną stronę. W przeciwnym przypadku wyświetlany jest odpowiedni monit o błędzie wprowadzonych danych.

### UC02 Rejestracja

Jako użytkownik niezarejestrowany,

chcę utworzyć nowe konto użytkownika,

aby korzystać z pełnej funkcjonalności serwisu.  

Scenariusz:

1.  Użytkownik niezarejestrowany przechodzi do podstrony z formularzem rejestracji.
    
2.  Wyświetlany jest formularz rejestracji.
    
3.  Obowiązkowe pola formularza to adres e-mail oraz dwukrotnie powtórzone hasło. Dodatkowo rejestrujący się użytkownik może wprowadzić dane osobowe oraz adresowe, które będą wymagane podczas realizacji zamówienia.
    
4.  Po naciśnięciu przycisku zatwierdzenia sprawdzana jest poprawność formatu adresu e-mail oraz zgodność obydwóch pól z hasłem.
    
5.  Dane o nowym użytkowniku są zapisane w tabeli użytkowników, a na jego adres e-mail wysyłana jest automatyczna wiadomość aktywacyjna.
    
6.  Po aktywacji konta osoba logująca się podanym adresem e-mail jest pełnoprawnym użytkownikiem serwisu. Brak aktywacji wiąże się z usunięciem nieaktywowanego konta po 24 godzinach.

### UC03 Wylogowanie

Jako użytkownik,

chcę wylogować się,

aby zakończyć sesję i opuścić serwis.  

Scenariusz:

1.  Użytkownik naciska znajdujący się na każdej podstronie przycisk “Wyloguj”.
    
2.  Tracone są uprawnienia użytkownika oraz dostęp do pełnej funkcjonalności serwisu.
    
3.  Sesja użytkownika oraz tymczasowe dane zostają wyczyszczone.

### UC04 Przeglądanie artykułów

Jako użytkownik niezalogowany,

chcę przeglądać dostępne artykuły,

aby dodać komentarz do jednego z nich.

Scenariusz:

1.  Użytkownik przechodzi do zakładki artykuły.
    
2.  Na stronie przedstawione są artykuły, ułożone stronami i posortowane datą dodania.
    
3.  Użytkownik może przewijać stronę, zmieniać numer strony w celu znalezienia poszukiwanego artykułu.

### UC05 Przeglądanie ciekawostek

Jako użytkownik niezalogowany,

chcę przeglądać dostępne ciekawostki,

aby zapoznać się z nimi.

Scenariusz:

1.  Użytkownik przechodzi do zakładki artykuły.
    
2.  Na stronie przedstawione obok artykułów dostępny jest panel ciekawostek.
    
3.  Użytkownik może przewijać stronę, zmieniać numer strony w celu znalezienia poszukiwanej ciekawostki.

### UC06 Dodanie artykułu

Jako administrator,

chcę dodać artykuł,

aby dodać komentarz do jednego z nich.

Scenariusz:

1.  Administrator przechodzi do panelu administratora i wybiera zakładkę artykuły.
    
2.  W prawym górnym rogu wybiera Dodaj artykuł i przechodzi do formularza.
    
3.  Uzupełnia pola formularza, takie jak tytuł, zawartość oraz datę publikacji.
4. Akceptuje dodatnie artykułu.

### UC07 Zarządzanie statusem artykułu

Jako administrator,

chcę zmienić status artykułu na odpublikowany,

aby nie był widoczny na stronie głównej.

Scenariusz:

1.  Administrator przechodzi do panelu administratora i wybiera zakładkę artykuły.
    
2.  Wyszukuje artykułu który chce odpublikować .
    
3.  Klikając w odpowiedni przycisk odpublikowuje artykuł. Artykuł przechodzi na początek listy odpublikowanych artykułów.

### UC08 Usuwanie artykułu

Jako administrator,

chcę usunąć artykuł,

aby nie udostępniać go dalej .

Scenariusz:

1.  Administrator przechodzi do panelu administratora i wybiera zakładkę artykuły.
    
2.  Wyszukuje artykułu który chce usunąć.
    
3.  Klikając w odpowiedni przycisk usuwa artykuł.

### UC09 Edytowanie artykułu

Jako administrator,

chcę edytować artykuł,

aby dopisać akapit.  

Scenariusz:

1.  Administrator przechodzi do panelu administratora.
    
2.  Przechodzi do zakładki artykuły i wyszukuje interesujący go artykuł.
    
3.  Poprzez wybranie przycisku Edytuj przełącza się w tryb edycji.
    
4.  Po zakończeniu modyfikacji akceptuje zmiany lub je porzuca.

### UC10 Dodanie ciekawostki 

Jako administrator,  

chcę dodać ciekawostkę, 

aby przyciągnąć uwagę czytelników.  

Scenariusz:  

1. Administrator przechodzi do panelu administratora i wybiera zakładkę ciekawostki.

2. W prawym górnym rogu wybiera Dodaj ciekawostkę i przechodzi do formularza.

3. Uzupełnia pola formularza, takie jak tytuł, opis oraz link do źródła.

4. Akceptuje dodatnie ciekawostki.

### UC11 Usuwanie ciekawostki  

Jako administrator,  

chcę usunąć ciekawostkę,  

aby nie udostępniać jej dalej .  

Scenariusz:  

1. Administrator przechodzi do panelu administratora i wybiera zakładkę ciekawostki.

2. Wyszukuje ciekawostkę którą chce usunąć.

3. Klikając w odpowiedni przycisk usuwa ciekawostkę.

### UC12 Edytowanie ciekawostki

Jako administrator,

chcę edytować ciekawostkę,

aby zmienić tytuł.  

Scenariusz:

1.  Administrator przechodzi do panelu administratora.
    
2.  Przechodzi do zakładki ciekawostki i wyszukuje interesującą go ciekawostkę.
    
3.  Poprzez wybranie przycisku Edytuj przełącza się w tryb edycji.
    
4.  Po zakończeniu modyfikacji akceptuje zmiany lub je porzuca.

### UC13 Dodanie tagu

Jako administrator,  

chcę dodać tag, 

aby otagować artykuł.  

Scenariusz:  

1. Administrator przechodzi do panelu administratora i wybiera zakładkę tagi.

2. W prawym górnym rogu wybiera Dodaj tag i przechodzi do formularza.

3. Uzupełnia pola formularza, takie jak nazwa i wybiera artykuły, w których ma być dodany.

4. Akceptuje dodatnie tagu.

### UC14 Usuwanie tagu

Jako administrator,  

chcę usunąć tag,  

aby nie widniał przy artykułach .  

Scenariusz:  

1. Administrator przechodzi do panelu administratora i wybiera zakładkę tagi.

2. Wyszukuje tag który chce usunąć.

3. Klikając w odpowiedni przycisk usuwa tag.

### UC15 Dodanie komentarza

Jako użytkownik zalogowany,  

chcę dodać komentarz, 

aby dołączyć do dyskusji pod artykułem.  

Scenariusz:  

1. Użytkownik przechodzi do wybranego artykułu.

2. Przesuwa na sam dół, do sekcji komentarzy.

3. Uzupełnia pole i klika dodaj komentarz.


### UC16 Usuwanie komentarza

Jako użytkownik zalogowany,  

chcę usunąć komentarz, 

aby nie widniał pod artykułem.
Scenariusz:  

1. Użytkownik przechodzi do panelu swojego konta i zakładki komentarze.

2. Wyszukuje komentarz do usunięcia.

3. Klikając w odpowiedni przycisk usuwa komentarz.


### UC17 Edytowanie danych

Jako użytkownik,

chcę zmienić dane swojego konta,

aby zaktualizować swój adres e-mail.  

Scenariusz:

1.  Zalogowany użytkownik przechodzi do panelu swojego konta i zakładki dane.
    
2.  Modyfikuje istniejące dane .
    
3.  Akceptuje zmiany odpowiednim przyciskiem lub je odrzuca.
    
### UC18 Wysyłanie wiadomości kontaktowej

Jako użytkownik niezalogowany,

chcę wysłać pytanie do administratora,

aby prosić o zgodę na wykorzystanie jego artykułu.  

Scenariusz:

1.  Niezalogowany użytkownik przechodzi do zakładki kontakt.
    
2.  Uzupełnia pola formularza, takie jak e-mail i treść wiadomości .
    
3.  Wysyła zapytanie wybierając przycisk Wyślij.

## Przykładowe diagramy BPMN
Dodawanie artykułu i komentarza
![diagram BPMN](https://pics.tinypic.pl/i/00978/89x67a9ral0s.png)

Wysłanie wiadomości poprzez formularz kontaktowy
![diagram BPMN](https://pics.tinypic.pl/i/00978/bu9cmwgorta5.png)

## Przykładowe diagramy sekwencji UML
Wysyłanie wiadomości kontaktowej.
![diagram sekwencji UML](https://pics.tinypic.pl/i/00978/en1jy6tv9e0l.png)

Rejestracja i dodanie komentarza.
![diagram sekwencji UML](https://pics.tinypic.pl/i/00978/7g50ln1a8znp.png)
## Diagram bazy danych

![Diagram bazy danych](https://files.tinypic.pl/i/00978/wgbdfmyfy35p.png)

## Wykres Gantta
![Wykres Gantta](https://files.tinypic.pl/i/00978/qd9xa5xwthkb.png)


## Zagrożenia

| Lp. | Zagrożenie                                                  | Wielkość szkody (S) |                       | Prawdopodobieństwo powstania szkody (P) |                    | Ryzyko (W) , W = S x P |                             |
|-----|-------------------------------------------------------------|---------------------|-----------------------|-----------------------------------------|--------------------|------------------------|-----------------------------|
| 1.  | Atak na system bazodanowy                                   | S=3                 | Znaczne szkody        | P=4                                     | dosyć częste       | W=12                   | ryzyko niedopuszczalne      |
| 2.  | Awaria serwera hostującego serwis i bazę danych             | S=2                 | Wymierne szkody       | P=3                                     | prawdopodobne      | W=6                    | dopuszczalna akceptowalność |
| 3.  | Błąd aplikacji serwisu uniemożliwiający korzystanie z niego | S=2                 | Wymierne szkody       | P=3                                     | prawdopodobne      | W=6                    | dopuszczalna akceptowalność |
| 4.  | Zerwanie łączności z serwerem                               | S=1                 | Znikome szkody        | P=4                                     | dosyć częste       | W=4                    | dopuszczalna akceptowalność |
| 5.  | Powódź w lokalizacji serwera                                | S=4                 | Ciężkie szkody        | P=2                                     | mało prawdopodobne | W=8                    | dopuszczalna akceptowalność |
| 6.  | Pożar w lokalizacji serwera                                 | S=5                 | Bardzo ciężkie szkody | P=2                                     | mało prawdopodobne | W=10                   | ryzyko niedopuszczalne      |


