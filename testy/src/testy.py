from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import sys, os, time
from urllib.parse import urlparse, parse_qs

service = Service(executable_path="chromedriver.exe")
driver = webdriver.Chrome(service=service)

driver.get("https://www.jakubmartynski.pl/projekt/index.php")

try:

    # Poczekaj na załadowanie strony (możesz dostosować czas oczekiwania według potrzeb)
    WebDriverWait(driver, 10).until(EC.title_contains("Logowanie"))
    print("Udało się przejść na stronę logowania (index.php)")

    # Zaloguj się używając danych j.martynski2002@gmail.com #admin
    driver.find_element(By.NAME, "email").send_keys("j.martynski2002@gmail.com")
    driver.find_element(By.NAME, "haslo").send_keys("NaDzIeJa_76!")
    driver.find_element(By.CLASS_NAME, "btn-login").click()
    # Poczekaj na przejście do strony admina po zalogowaniu
    WebDriverWait(driver, 10).until(EC.title_contains("Strona administratora"))
    print("Udało się przejść na stronę administratora (admin.php)")
    # Wyloguj się
    driver.find_element(By.NAME, "wyloguj").click()
    # Poczekaj na przejście do strony logowania po wylogowaniu
    WebDriverWait(driver, 10).until(EC.title_contains("Logowanie"))
    print("Pomyślnie wylogowano")

    # Zaloguj się używając danych 109266@g.elearn.uz.zgora.pl #user
    driver.find_element(By.NAME, "email").send_keys("109266@g.elearn.uz.zgora.pl")
    driver.find_element(By.NAME, "haslo").send_keys("NaDzIeJa_76!")
    driver.find_element(By.CLASS_NAME, "btn-login").click()
    # Poczekaj na przejście do strony użytkownika po zalogowaniu
    WebDriverWait(driver, 10).until(EC.title_contains("Strona użytkownika"))
    print("Udało się przejść na stronę użytkownika (user.php)")
    # Wyloguj się
    driver.find_element(By.NAME, "wyloguj").click()
    # Poczekaj na przejście do strony logowania po wylogowaniu
    WebDriverWait(driver, 10).until(EC.title_contains("Logowanie"))
    print("Pomyślnie wylogowano")

    # Sprawdź, czy na stronie znajduje się link do rejestracji
    link_rejestracja = driver.find_element(By.XPATH, "//a[@href='rejestracja.php']")
    link_rejestracja.click()
    WebDriverWait(driver, 10).until(EC.title_contains("Rejestracja"))
    print("Udało się przejść na stronę rejestracji (rejestracja.php)")

    # Powrót do strony głównej
    driver.back()

    # Sprawdź, czy na stronie znajduje się link do przypomnienia hasła
    link_przypomnij_haslo = driver.find_element(By.XPATH, "//a[@href='przypomnij.php']")
    link_przypomnij_haslo.click()
    WebDriverWait(driver, 10).until(EC.title_contains("Przypomnienie"))
    print("Udało się przejść na stronę przypomnienia hasła (przypomnij.php)")

    # Powrót do strony głównej
    driver.back()

    # to samo ale po stronie ukraińskiej

    # zmień język
    driver.find_element(By.NAME, "jezyk").click()
    WebDriverWait(driver, 10).until(EC.title_contains("Логін"))
    print("Udało się przejść na stronę po ukraińsku (ukr/index.php)")

    # Zaloguj się używając danych j.martynski2002@gmail.com #admin
    driver.find_element(By.NAME, "email").send_keys("j.martynski2002@gmail.com")
    driver.find_element(By.NAME, "haslo").send_keys("NaDzIeJa_76!")
    driver.find_element(By.CLASS_NAME, "btn-login").click()
    # Poczekaj na przejście do strony admina po zalogowaniu
    WebDriverWait(driver, 10).until(EC.title_contains("Сторінка адміністратора"))
    print("Udało się przejść na stronę administratora (ukr/admin.php)")
    # Wyloguj się
    driver.find_element(By.NAME, "wyloguj").click()
    # Poczekaj na przejście do strony logowania po wylogowaniu
    WebDriverWait(driver, 10).until(EC.title_contains("Логін"))
    print("Pomyślnie wylogowano (ukr)")

    # Zaloguj się używając danych 109266@g.elearn.uz.zgora.pl #user
    driver.find_element(By.NAME, "email").send_keys("109266@g.elearn.uz.zgora.pl")
    driver.find_element(By.NAME, "haslo").send_keys("NaDzIeJa_76!")
    driver.find_element(By.CLASS_NAME, "btn-login").click()
    # Poczekaj na przejście do strony użytkownika po zalogowaniu
    WebDriverWait(driver, 10).until(EC.title_contains("Сторінка користувача"))
    print("Udało się przejść na stronę użytkownika (ukr/user.php)")
    # Wyloguj się
    driver.find_element(By.NAME, "wyloguj").click()
    # Poczekaj na przejście do strony logowania po wylogowaniu
    WebDriverWait(driver, 10).until(EC.title_contains("Логін"))
    print("Pomyślnie wylogowano (ukr)")

    # Sprawdź, czy na stronie znajduje się link do rejestracji
    link_rejestracja = driver.find_element(By.XPATH, "//a[@href='rejestracja.php']")
    link_rejestracja.click()
    WebDriverWait(driver, 10).until(EC.title_contains("Реєстрація"))
    print("Udało się przejść na stronę rejestracji (ukr/rejestracja.php)")

    # Powrót do strony głównej
    driver.back()

    # Sprawdź, czy na stronie znajduje się link do przypomnienia hasła
    link_przypomnij_haslo = driver.find_element(By.XPATH, "//a[@href='przypomnij.php']")
    link_przypomnij_haslo.click()
    WebDriverWait(driver, 10).until(EC.title_contains("Нагадування"))
    print("Udało się przejść na stronę przypomnienia hasła (ukr/przypomnij.php)")

    # Powrót do strony głównej
    driver.back()

    driver.find_element(By.NAME, "jezyk").click()
    WebDriverWait(driver, 10).until(EC.title_contains("Logowanie"))
    print("Udało się powrócić na stronę logowania po polsku (index.php)")

    # Zaloguj się używając danych 109266@g.elearn.uz.zgora.pl #user
    driver.find_element(By.NAME, "email").send_keys("109266@g.elearn.uz.zgora.pl")
    driver.find_element(By.NAME, "haslo").send_keys("NaDzIeJa_76!")
    driver.find_element(By.CLASS_NAME, "btn-login").click()
    # Poczekaj na przejście do strony użytkownika po zalogowaniu
    WebDriverWait(driver, 10).until(EC.title_contains("Strona użytkownika"))
    print("Udało się przejść na stronę użytkownika (user.php)")

    # Przesłanie pliku
    file_name = "test.txt"
    file_path = os.path.abspath(file_name)
    with open(file_path, "w") as file:
        file.write("To jest testowy plik.")
    driver.find_element(By.ID, "fileInput").send_keys(file_path)
    driver.find_element(By.NAME, "submit").click()
    # Czekanie, aż nazwa pliku pojawi się na stronie
    WebDriverWait(driver, 10).until(lambda driver: file_name in driver.page_source)
    print("Plik test.txt został pomyślnie przesłany.")

    # Przesłanie pliku
    file_name = "test2.txt"
    file_path = os.path.abspath(file_name)
    with open(file_path, "w") as file:
        file.write("To jest testowy plik.")
    driver.find_element(By.ID, "fileInput").send_keys(file_path)
    driver.find_element(By.NAME, "submit").click()
    # Czekanie, aż nazwa pliku pojawi się na stronie
    WebDriverWait(driver, 10).until(lambda driver: file_name in driver.page_source)
    print("Plik test2.txt został pomyślnie przesłany.")

    # Wyszukiwanie pliku 'test.txt'
    search_box = driver.find_element(By.NAME, "fraza")
    search_box.send_keys("test.txt")
    search_box.submit()  # Submit the form
    # Czekanie, aż URL zawiera frazę 'test.txt'
    WebDriverWait(driver, 10).until(
        lambda d: 'fraza' in parse_qs(urlparse(d.current_url).query) and parse_qs(urlparse(d.current_url).query)['fraza'][0] == 'test.txt'
    )
    # Sprawdzanie, czy 'test2.txt' nie jest wyświetlany
    page_source = driver.page_source
    if "test2.txt" not in page_source:
        print("Plik 'test2.txt' nie jest widoczny po wyszukaniu 'test.txt', co jest zachowaniem oczekiwanym.")
    else:
        print("Plik 'test2.txt' jest nadal widoczny, co jest błędem.")

    WebDriverWait(driver, 10).until(EC.visibility_of_element_located((By.XPATH, "//button[text()='Pobierz']")))
    # Kliknięcie przycisku Pobierz dla 'test.txt'
    download_buttons = driver.find_elements(By.XPATH, "//button[text()='Pobierz']")
    if download_buttons:
        download_buttons[0].click()
        print("Przycisk Pobierz został kliknięty dla pliku test.txt")
    else:
        print("Nie znaleziono przycisku Pobierz dla pliku test.txt")

    # Ścieżka do folderu Pobrane (zakładając, że jest to standardowa lokalizacja dla systemu Windows)
    download_folder = os.path.join(os.path.expanduser("~"), "Downloads")
    # Nazwa pliku do sprawdzenia
    file_name = "test.txt"
    file_path = os.path.join(download_folder, file_name)
    # Odczekaj, aby dać przeglądarce czas na pobranie pliku
    time.sleep(2)  # Czas w sekundach; dostosuj zależnie od prędkości połączenia internetowego
    # Sprawdź, czy plik istnieje w folderze Pobrane
    if os.path.exists(file_path):
        print(f"Plik {file_name} został pobrany.")
        # Usunięcie pliku
        os.remove(file_path)
        print(f"Plik {file_name} został usunięty.")
    else:
        print(f"Plik {file_name} nie został znaleziony w folderze Pobrane.")


    # # sprawdzenie udostępniania # #


    # Znajdowanie przycisku "Usuń" dla przesłanego pliku
    delete_button = driver.find_element(By.XPATH, f"//h3[contains(text(), '{file_name}')]/ancestor::li/following-sibling::li/form[@action='skrypty/usun_plik.php']/button")
    delete_button.click()
    # Sprawdzenie, czy plik został usunięty
    WebDriverWait(driver, 10).until(lambda driver: file_name not in driver.page_source)
    print("Plik test.txt został pomyślnie usunięty.")
    file_name = "test2.txt"
    # Znajdowanie przycisku "Usuń" dla przesłanego pliku
    delete_button = driver.find_element(By.XPATH, f"//h3[contains(text(), '{file_name}')]/ancestor::li/following-sibling::li/form[@action='skrypty/usun_plik.php']/button")
    delete_button.click()
    # Sprawdzenie, czy plik został usunięty
    WebDriverWait(driver, 10).until(lambda driver: file_name not in driver.page_source)
    print("Plik test2.txt został pomyślnie usunięty.")

    # Wyloguj się
    driver.find_element(By.NAME, "wyloguj").click()
    # Poczekaj na przejście do strony logowania po wylogowaniu
    WebDriverWait(driver, 10).until(EC.title_contains("Logowanie"))
    print("Pomyślnie wylogowano")

    # Zaloguj się używając danych 109266@g.elearn.uz.zgora.pl #user
    driver.find_element(By.NAME, "email").send_keys("109266@g.elearn.uz.zgora.pl")
    driver.find_element(By.NAME, "haslo").send_keys("123")
    driver.find_element(By.CLASS_NAME, "btn-login").click()
    # Poczekaj na przejście do strony użytkownika po zalogowaniu
    WebDriverWait(driver, 10).until(EC.title_contains("Logowanie"))
    print("Nie udało się zalogować czyli jest git bo tak miało być")
    # Zaloguj się używając danych 109266@g.elearn.uz.zgora.pl #user
    driver.find_element(By.NAME, "email").send_keys("109266@g.elearn.uz.zgora.pl")
    driver.find_element(By.NAME, "haslo").send_keys("123")
    driver.find_element(By.CLASS_NAME, "btn-login").click()
    # Poczekaj na przejście do strony użytkownika po zalogowaniu
    WebDriverWait(driver, 10).until(
        lambda d: 'error' in parse_qs(urlparse(d.current_url).query) and parse_qs(urlparse(d.current_url).query)['error'][0] == '2'
    )
    print("Pomyślnie zablokowano użytkownika")

finally:

    # Zakończenie testu
    print("\nWszystkie testy zakończone")
    driver.quit()
    sys.exit()
