#include <stdio.h>
#include <string.h>
#include <ctype.h>
#include <stdbool.h>

char letra,proxima;
char palavra[100];
int linha = 1;
FILE *arq,*arqLex;

void ler();
void lerNumero();
void lerNumeroHexa();
void lerPalavra();
int isLetra(char l);
int isLetraAF(char l);
int isNumero(char l);
int isReservada();


int main(int argc, char **argv){
    char nomearq[20];
    arqLex = fopen("lexico.txt","w");
    if(!arqLex){
        printf("N�o criou o arquivo Lexico.\n");
        return 0;
    }
    if(argc > 1){
        arq = fopen(argv[1],"r");
    }else{
        printf("Informe o nome do arquivo: ");
        scanf("%s",&nomearq);
        arq = fopen(nomearq,"r");
    }

    if(!arq){
        printf("N�o abriu!,\n");
    }
    else{
        printf("Abriu ARQUIVO\n");
        //letra = fgetc(arq);
        proxima = ' ';
        while(proxima != EOF){
            ler();
            //printf("%c",letra);
            switch(letra){
                case 'a': case 'b': case 'c': case 'd': case 'e': case 'f': case 'g': case 'h': case 'i': case 'j': case 'k': case 'l': case 'm':
                case 'n': case 'o': case 'p': case 'q': case 'r': case 's': case 't': case 'u': case 'v': case 'w': case 'x': case 'y': case 'z':
                case 'A': case 'B': case 'C': case 'D': case 'E': case 'F': case 'G': case 'H': case 'I': case 'J': case 'K': case 'L': case 'M':
                case 'N': case 'O': case 'P': case 'Q': case 'R': case 'S': case 'T': case 'U': case 'V': case 'W': case 'X': case 'Y': case 'Z':
                case '_': lerPalavra(); break;
                case '0': case '1': case '2': case '3': case '4': case '5': case '6': case '7': case '8': case '9': lerNumero();

                case ';' : fprintf(arqLex,"%d ;\n", linha); ler();  break;
                case ',' : fprintf(arqLex,"%d ,\n", linha); break;
                case ':' : fprintf(arqLex,"%d :\n", linha); break;
                case '.' : if((proxima >= 48) && (proxima <= 57)){
                              lerNumero(); break;
                           }
                           else{
                              fprintf(arqLex,"%d .\n", linha); break;
                           }
                case '{' : fprintf(arqLex,"%d {\n", linha); break;
                case '}' : fprintf(arqLex,"%d }\n", linha); break;
                case '(' : fprintf(arqLex,"%d (\n", linha); break;
                case ')' : fprintf(arqLex,"%d )\n", linha); break;
                case '[' : fprintf(arqLex,"%d [\n", linha); break;
                case ']' : fprintf(arqLex,"%d ]\n", linha); break;
                case '+' : if(proxima == '+'){ // ++
                             ler();
                             fprintf(arqLex,"%d 12\n", linha); break;
                           }
                           else{
                              if(proxima == '='){  // +=
                                ler();
                                fprintf(arqLex,"%d +=\n", linha); break;
                              }
                              else{
                                 if((proxima >= 48) && (proxima <= 57)){
                                    lerNumero(); break;
                                 }
                                else{
                                   fprintf(arqLex,"%d +\n", linha); break;
                                }
                              }
                           }
                case '-' : if(proxima == '-'){ // --
                             ler();
                             fprintf(arqLex,"%d --\n", linha); break;
                           }
                           else{
                              if(proxima == '='){  // -=
                                ler();
                                fprintf(arqLex,"%d -=\n", linha); break;
                              }
                              else{
                                 if((proxima >= 48) && (proxima <= 57)){
                                    lerNumero(); break;
                                 }
                                else{
                                   fprintf(arqLex,"%d -\n", linha); break;
                                }
                              }
                           }
                case '#' : fprintf(arqLex,"%d #\n", linha); break;
                case '*' : if(proxima == '='){ // *=
                             ler();
                             fprintf(arqLex,"%d *=\n", linha); break;
                           }
                           else{
                              fprintf(arqLex,"%d *\n", linha); break;
                           }
                case '/' : if(proxima == '='){ // /=
                             ler();
                             fprintf(arqLex,"%d /=\n", linha); break;
                           }
                           else if(proxima == '/'){
                             while(proxima != '\n'){
                                ler();
                             }
                             fprintf(arqLex,"%d coment\n", linha); break;

                           }
                           else if(proxima == '*'){
                            

                            int continua = 1;
                            while(continua){
                                ler();
                                
                                if(proxima == '*'){
                                    ler();
                                    if(proxima == '/') continua = 0;
                                }

                            }

                            ler();
                            fprintf(arqLex,"%d coment\n", linha); break;
                             
                           }
                           else{
                              fprintf(arqLex,"%d /\n", linha); break;
                           }
                case '%' : if(proxima == '='){ // %=
                             ler();
                             fprintf(arqLex,"%d %=\n", linha); break;
                           }
                           else{
                              fprintf(arqLex,"%d %\n", linha); break;
                           }
                case '?' : fprintf(arqLex,"%d ?\n", linha); break;
                case '!' : if(proxima == '='){ // !=
                             ler();
                             fprintf(arqLex,"%d !=\n", linha); break;
                           }
                           else{
                              fprintf(arqLex,"%d !\n", linha); break;
                           }
                case '=' : if(proxima == '='){ // ==
                             ler();
                             fprintf(arqLex,"%d ==\n", linha); break;
                           }
                           else{
                              fprintf(arqLex,"%d =\n", linha); break;
                           }
                case '<' : if(proxima == '='){ // <=
                             ler();
                             fprintf(arqLex,"%d <=\n", linha); break;
                           }
                           else{
                              if(proxima == '<'){ // <<
                                 ler();
                                 fprintf(arqLex,"%d <<\n", linha); break;
                              }
                              else{
                                 fprintf(arqLex,"%d <\n", linha); break;
                              }
                           }
                case '>' : if(proxima == '='){ // >=
                             ler();
                             fprintf(arqLex,"%d >=\n", linha); break;
                           }
                           else{
                              if(proxima == '>'){ // >>
                                 ler();
                                 fprintf(arqLex,"%d >>\n", linha); break;
                              }
                              else{
                                 fprintf(arqLex,"%d >\n", linha); break;
                              }
                           }
                case '|' : if(proxima == '|'){ // ||
                             ler();
                             fprintf(arqLex,"%d ||\n", linha); break;
                           }
                           else{
                              fprintf(arqLex,"%d |\n", linha); break;
                           }
                case '&' : if(proxima == '&'){ // &&
                             ler();
                             fprintf(arqLex,"%d |\n", linha); break;
                           }
                           else{
                              fprintf(arqLex,"%d &\n", linha); break;
                           }
            }
        }
        fclose(arq);
    }
}

void ler(){
    letra = proxima;
    if(letra == '\n') linha++;

    proxima = fgetc(arq);
}

void lerNumero(){

    if(proxima == 'x'){
        lerNumeroHexa();
        return 0;
    }

    char palavra[100];
    int p=0, test=1, first;
    palavra[p++]=letra;
    do{
       if(isNumero(proxima)){
          ler();
          palavra[p++]=letra;
       }
       else if(proxima == '.'){

            ler();
            palavra[p++]=letra;
            
            bool flage = false;
            bool flagmais = false;
            bool flagmenos = false;
            first = 0;

            while(1){
                if(isdigit(proxima)){
                    ler();
                     palavra[p++]=letra;
                    continue;
                }else if(proxima == 'e' && !flage && first != 0){
                    ler();
                     palavra[p++]=letra;
                    flage = true;
                    continue;
                }else if(proxima == '+' && !flagmais && !flagmenos && first != 0){
                    ler();
                     palavra[p++]=letra;
                    flagmais = true;
                    continue;
                }else if(proxima == '-' && !flagmenos && !flagmais && first != 0){
                    ler();
                     palavra[p++]=letra;
                    flagmenos = true;
                    continue;
                }else{
                     test=0;
                    break;
                }
            }
      }
       else{
           break;
       }
    }while(test);
    palavra[p]='\0';
    printf("%s\n",palavra);
    fprintf(arqLex,"%d num %s\n",linha, palavra); return 0;

}

void lerNumeroHexa(){
    char numero[100];
    int p=0;
    numero[p++]=letra;
    numero[p++]=proxima;
    ler();

    do{
       if(isNumero(proxima)){
          ler();
          numero[p++]=letra;
       }else if(isLetraAF(proxima)){
          ler();
          numero[p++]=letra;
       }
       else{
           break;
       }
    }while(1);
     numero[p]='\0';
    printf("%s\n",numero);

    fprintf(arqLex,"%d numhex %s\n",linha, numero); return 0;

}

void lerPalavra(){
    char palavra[100];
    int p=0;
    palavra[p++]=letra;
    do{
       if(isLetra(proxima)){
          ler();
          palavra[p++]=letra;
       }
       else{
           break;
       }
    }while(1);
    palavra[p]='\0';

    if( isReservada(palavra) )
      printf("<%s>\n",palavra);

}

int isLetraAF(char l){
    switch(l){
       case 'a': case 'b': case 'c': case 'd': case 'e': case 'f': case 'A': case 'B': case 'C': case 'D': case 'E': case 'F': 
           return 1;
       default:
           return 0;
    }
}

int isLetra(char l){
    return (isalpha(l) || isdigit(l) || l == '_') ? 1 : 0;
}

int isNumero(char l){
    return isdigit(l) ? 1 : 0;
}

int isReservada(char *palavra){
    if(!strcmp(palavra,"auto")) {fprintf(arqLex,"%d auto\n", linha); return 1;}
    if(!strcmp(palavra,"break")) {fprintf(arqLex,"%d break\n", linha); return 1;}
    if(!strcmp(palavra,"case")) {fprintf(arqLex,"%d case\n", linha); return 1;}
    if(!strcmp(palavra,"char")) {fprintf(arqLex,"%d char\n", linha); return 1;}
    if(!strcmp(palavra,"const")) {fprintf(arqLex,"%d const\n", linha); return 1;}
    if(!strcmp(palavra,"continue")) {fprintf(arqLex,"%d continue\n", linha); return 1;}
    if(!strcmp(palavra,"default")) {fprintf(arqLex,"%d default\n", linha); return 1;}
    if(!strcmp(palavra,"do")) {fprintf(arqLex,"%d do\n", linha); return 1; return 1;}
    if(!strcmp(palavra,"double")) {fprintf(arqLex,"%d double\n", linha); return 1;}
    if(!strcmp(palavra,"else")) {fprintf(arqLex,"%d else\n", linha); return 1;}
    if(!strcmp(palavra,"enum")) {fprintf(arqLex,"%d enum\n", linha); return 1;}
    if(!strcmp(palavra,"extern")) {fprintf(arqLex,"%d extern\n", linha); return 1;}
    if(!strcmp(palavra,"float")) {fprintf(arqLex,"%d float\n", linha); return 1;}
    if(!strcmp(palavra,"for")) {fprintf(arqLex,"%d for\n", linha); return 1;}
    if(!strcmp(palavra,"goto")) {fprintf(arqLex,"%d goto\n", linha); return 1;}
    if(!strcmp(palavra,"if")) {fprintf(arqLex,"%d if\n", linha); return 1;}
    if(!strcmp(palavra,"int")) {fprintf(arqLex,"%d int\n", linha); return 1;}
    if(!strcmp(palavra,"long")) {fprintf(arqLex,"%d long\n", linha); return 1;}
    if(!strcmp(palavra,"register")) {fprintf(arqLex,"%d register\n", linha); return 1;}
    if(!strcmp(palavra,"return")) {fprintf(arqLex,"%d return\n", linha); return 1;}
    if(!strcmp(palavra,"short")) {fprintf(arqLex,"%d short\n", linha); return 1;}
    if(!strcmp(palavra,"signed")) {fprintf(arqLex,"%d signed\n", linha); return 1;}
    if(!strcmp(palavra,"sizeof")) {fprintf(arqLex,"%d sizeof\n", linha); return 1;}
    if(!strcmp(palavra,"static")) {fprintf(arqLex,"%d static\n", linha); return 1;}
    if(!strcmp(palavra,"struct")) {fprintf(arqLex,"%d struct\n", linha); return 1;}
    if(!strcmp(palavra,"switch")) {fprintf(arqLex,"%d switch\n", linha); return 1;}
    if(!strcmp(palavra,"typedef")) {fprintf(arqLex,"%d typedef\n", linha); return 1;}
    if(!strcmp(palavra,"union")) {fprintf(arqLex,"%d union\n", linha); return 1;}
    if(!strcmp(palavra,"unsigned")) {fprintf(arqLex,"%d unsigned\n", linha); return 1;}
    if(!strcmp(palavra,"void")) {fprintf(arqLex,"%d void\n", linha); return 1;}
    if(!strcmp(palavra,"volatile")) {fprintf(arqLex,"%d volatile\n", linha); return 1;}
    if(!strcmp(palavra,"while")) {fprintf(arqLex,"%d while\n", linha); return 1; }
    fprintf(arqLex,"%d id %s\n",linha, palavra); return 0;
}