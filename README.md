# Redaxo Backend Theme

![Screenshot](be_theme.png)

Ein Plugin für das Redaxo AddOn `be_style`

## Installation

In den Plugins Ordner von `be_style` das `be_theme` klonen..

```sh
cd redaxo/src/addons/be_style/plugins
git clone https://github.com/clicksports/be_theme.git
```

Falls notwendig, das Plugin noch auf den Server hochladen und das Plugin `be_theme` installieren und anschließend das Plugin `redaxo` deaktivieren. 

## Anpassungen

Damit die Änderungen in SCSS wirksam werden, muss der SASS-Compiler in der `be_style/plugins/be_theme/package.yml` aktiviert werden:

In der `package.yml` **vom Plugin be_theme** den SASS-Compiler aktivieren

*be_style/plugins/be_theme/package.yml*
```yaml
compile: 1
```

Zuletzt das Backend neu laden (damit die SASS-Scripte abgearbeitet werden und neue CSS-Styles generieren) und den Compiler **im Plugin be_theme** ausschalten.

*be_style/plugins/be_theme/package.yml*
```yaml
compile: 0
```
