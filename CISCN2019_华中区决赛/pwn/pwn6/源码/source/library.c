// gcc library.c -fstack-protector-all -z now -s -no-pie -O1 -o library
#include <stdlib.h>
#include <stdio.h>
#include <unistd.h> 
#include <string.h>
#include <time.h>
static void *table[10];
static unsigned int edit_times=0;
int read_str(char *addr,unsigned int num)
{
	unsigned int i;
	char *tmp;
	if(num<=0)
		return 0;
	tmp=addr;
	for(i=0;;++i)
	{
		if(read(0,tmp,1) != 1)
			return 1;
		if(*tmp=='\n')
			break;
		++tmp;
		if(i==num)
			break;
	}
	*tmp=0;
	return 0;
}
int read_int()
{
	char s[10];
	memset(s,0,10);
	read_str(s,0xf);
	return atoi(s);
}
void menu()
{
    puts("1. new");
    puts("2. edit");
    puts("3. show");
    puts("4. delete");
    puts("5. exit");
    puts(">>");
}
void new()
{
	printf("Index:");
	unsigned int index=read_int();
	if(index <=9 && !table[index])
	{
		table[index]=malloc(32);
		printf("Input the name of the book:");
		read_str(table[index],32);
		puts("Done!");
	}
}
void edit()
{
	char tmp[10];
	printf("Index:");
	unsigned int index=read_int();
	if(index<=0x1f && table[index] && edit_times != 4)
	{
		printf("Input new name of the book:");
		read_str(table[index],32);
		edit_times++;
		puts("Done!");
	}
}
void show()
{
	char tmp[10];
	printf("Index:");
	unsigned int index=read_int();
	if(index<=9 && table[index])
	{
		puts((const char *)table[index]);
		puts("Done");
	}
}
void delete()
{
	char tmp[10];
	printf("Index:");
	unsigned int index=read_int();
	if(index<=9 && table[index])
	{
		free(table[index]);
		puts("Done");
	}
}
int main()
{
	int choose;
	char tmp[10];
	int key[19]={61, 73, 83, 97, 109, 113, 127, 131, 137, 139, 149, 151, 167, 179, 193, 199, 211, 223, 229}; 
	int i,j,k;
	long int password;
	int original;
	int valid;
	int input;
	setvbuf(stdin,0,2,0);
	setvbuf(stdout,0,2,0);
	srand((unsigned)time(NULL));
	for(k=0;k<4;k++){
		while(1){
			valid=1;
			password=rand()%199+64;
			for(i=0;i<19;i++){
				if((password%key[i])==0) valid=0;
			}
			if(valid) break;
		}
		original=password;
		for(i=0;i<4;i++){
			j=rand()%19;
			password=password*key[j];
		}
		password=password&0xFFFFFFFF;
		printf("%lx\n",password);
		input=read_int();
		if(input!=original){
			printf("Sorry, you can't get into the library. \n");
			exit(0);
		}
	}
	while(1)
	{
		puts("you can manage your library");
		menu();
		choose=read_int();;
		switch(choose)
		{
			case 1:new();break;
			case 2:edit();break;
			case 3:show();break;
			case 4:delete();break;
			case 5:exit(0);
			default:continue;
		}
	}
	return 0;
}