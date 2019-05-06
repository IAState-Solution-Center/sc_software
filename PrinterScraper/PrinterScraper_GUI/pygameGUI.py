import pygame
import scraper_obj


class PrinterGUI(object):

    def __init__(self, width=1000, height=480, fps=60):
        pygame.init()
        self.width = width
        self.height = height
        self.screen = pygame.display.set_mode((self.width, self.height))
        self.background = pygame.Surface(self.screen.get_size()).convert()
        self.background.fill((199, 255, 163))
        self.background.convert()
        self.fps = fps
        self.font = pygame.font.SysFont('comicsansmsttf', 34, bold=False)
        self.message = "No printers need action"
        self.done = False
        self.line_count = 0
        pygame.display.set_caption("Press ESC to quit or Return to refresh")
        self.printer_sad = pygame.image.load("crying_printer_small.png")
        self.printer_happy = pygame.image.load("cool_printer_small.png")

    def printer(self, width, height):
        text = self.font.render(self.message, True, (0, 0, 0))
        text_rectangle = text.get_rect()
        text_rectangle.center = (width, height)
        self.screen.blit(text, text_rectangle)

    def update_text(self, text="", offset=0):
        self.__init__()
        self.screen.blit(self.background, (0, 0))
        self.message = text
        self.printer(self.width // 2, self.height // 2 + offset)
        pygame.display.flip()

    def add_line(self, text=""):
        self.line_count += 1
        offset = self.line_count * 36
        self.message = text
        self.printer(self.width // 2, offset)
        pygame.display.flip()

    def update_background(self, color):
        if color == "RED":
            self.background.fill((255, 189, 124))
            self.background.convert()
        if color == "GREEN":
            self.background.fill((199, 255, 163))
            self.background.convert()
        self.screen.blit(self.background, (0, 0))
        pygame.display.flip()

    def check_updates(self):
        s = scraper_obj.Scraper()
        s.load_urls()
        if not s.printer_statuses:
            self.update_background("GREEN")
            self.update_text("No printers need action.")
            self.screen.blit(self.printer_happy, ((self.width // 2) - 45, self.height - 100))
        else:
            self.update_background("RED")
            for messages in s.printer_statuses:
                self.add_line(messages)
                self.screen.blit(self.printer_sad, ((self.width // 2) - 45, self.height - 100))
        self.line_count = 0
        print(s.printer_statuses)

    def run(self):
        clock = pygame.time.Clock()
        self.check_updates()
        time_passed = 0
        while not self.done:
            time_passed += clock.tick()
            for event in pygame.event.get():
                if event.type == pygame.QUIT:
                    self.done = True
                elif event.type == pygame.KEYDOWN:
                    if event.key == pygame.K_ESCAPE:
                        self.done = True
                    if event.key == pygame.K_RETURN:
                        self.check_updates()
                        time_passed = 0
            if time_passed > 200 * 1000:
                self.check_updates()
                time_passed = 0

            # ABRA CADABRA
            pygame.display.flip()


if __name__:
    p = PrinterGUI(1000, 480, 60)
    p.run()
